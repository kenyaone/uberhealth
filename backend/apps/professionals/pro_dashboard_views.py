"""
Professional dashboard — schedule, earnings, patients, notes.
All views require is_professional role.
"""
from rest_framework import status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from django.db.models import Sum, Count, Avg
from django.utils import timezone
from datetime import timedelta
from apps.consultations.models import Consultation
from apps.consultations.serializers import ConsultationSerializer
from apps.payments.models import ProfessionalPayout
from apps.assessments.models import Assessment
from apps.phr.models import MoodLog
from .models import Professional, ProfessionalAvailability
from .serializers import ProfessionalSerializer, AvailabilitySerializer


def require_professional(func):
    def wrapper(request, *args, **kwargs):
        if not request.user.is_authenticated or request.user.role != 'professional':
            return Response({'error': 'Professional account required.'}, status=status.HTTP_403_FORBIDDEN)
        try:
            request.professional = request.user.professional_profile
        except Professional.DoesNotExist:
            return Response({'error': 'No professional profile found.'}, status=status.HTTP_404_NOT_FOUND)
        return func(request, *args, **kwargs)
    wrapper.__name__ = func.__name__
    return wrapper


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_dashboard_summary(request):
    prof = request.professional
    now = timezone.now()
    month_start = now.replace(day=1, hour=0, minute=0, second=0, microsecond=0)

    consultations = Consultation.objects.filter(professional=prof)
    upcoming = consultations.filter(
        status__in=['confirmed', 'in_progress'],
        scheduled_at__gte=now
    ).order_by('scheduled_at')[:5]
    recent_completed = consultations.filter(
        status='completed'
    ).order_by('-actual_end')[:5]

    month_earnings = ProfessionalPayout.objects.filter(
        professional=prof,
        created_at__gte=month_start,
    ).aggregate(t=Sum('amount'))['t'] or 0

    total_earnings = ProfessionalPayout.objects.filter(
        professional=prof,
    ).aggregate(t=Sum('amount'))['t'] or 0

    pending_payout = ProfessionalPayout.objects.filter(
        professional=prof, status='pending'
    ).aggregate(t=Sum('amount'))['t'] or 0

    return Response({
        'professional': ProfessionalSerializer(prof).data,
        'stats': {
            'total_sessions': prof.total_sessions,
            'rating': str(prof.rating),
            'total_reviews': prof.total_reviews,
            'month_earnings': float(month_earnings),
            'total_earnings': float(total_earnings),
            'pending_payout': float(pending_payout),
        },
        'upcoming_sessions': ConsultationSerializer(upcoming, many=True).data,
        'recent_sessions': ConsultationSerializer(recent_completed, many=True).data,
    })


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_sessions(request):
    prof = request.professional
    status_filter = request.query_params.get('status')
    qs = Consultation.objects.filter(professional=prof).select_related('user').order_by('-scheduled_at')
    if status_filter:
        qs = qs.filter(status=status_filter)
    return Response(ConsultationSerializer(qs, many=True).data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_earnings(request):
    prof = request.professional
    now = timezone.now()

    # Monthly breakdown — last 6 months
    monthly = []
    for i in range(5, -1, -1):
        month_start = (now - timedelta(days=30 * i)).replace(
            day=1, hour=0, minute=0, second=0, microsecond=0
        )
        month_end = (month_start + timedelta(days=32)).replace(day=1)
        earned = ProfessionalPayout.objects.filter(
            professional=prof,
            created_at__gte=month_start,
            created_at__lt=month_end,
        ).aggregate(t=Sum('amount'))['t'] or 0
        sessions = Consultation.objects.filter(
            professional=prof,
            status='completed',
            actual_end__gte=month_start,
            actual_end__lt=month_end,
        ).count()
        monthly.append({
            'month': month_start.strftime('%b %Y'),
            'earned': float(earned),
            'sessions': sessions,
        })

    payouts = ProfessionalPayout.objects.filter(professional=prof).order_by('-created_at')[:20]
    payout_data = [{
        'id': p.id,
        'amount': float(p.amount),
        'status': p.status,
        'consultation_id': p.consultation.consultation_id,
        'created_at': p.created_at.isoformat(),
        'paid_at': p.paid_at.isoformat() if p.paid_at else None,
    } for p in payouts]

    total = ProfessionalPayout.objects.filter(professional=prof).aggregate(t=Sum('amount'))['t'] or 0
    pending = ProfessionalPayout.objects.filter(professional=prof, status='pending').aggregate(t=Sum('amount'))['t'] or 0
    paid = ProfessionalPayout.objects.filter(professional=prof, status='paid').aggregate(t=Sum('amount'))['t'] or 0

    return Response({
        'summary': {
            'total_earned': float(total),
            'pending_payout': float(pending),
            'total_paid': float(paid),
        },
        'monthly_breakdown': monthly,
        'recent_payouts': payout_data,
    })


@api_view(['GET'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_patients(request):
    prof = request.professional
    consultations = Consultation.objects.filter(
        professional=prof
    ).select_related('user').values('user_id', 'user__display_name').distinct()

    patients = []
    for c in consultations:
        uid = c['user_id']
        session_count = Consultation.objects.filter(professional=prof, user_id=uid).count()
        last_session = Consultation.objects.filter(
            professional=prof, user_id=uid
        ).order_by('-scheduled_at').first()

        # Fetch shared assessments (only if user shared them)
        shared_assessments = []
        shared_cons = Consultation.objects.filter(
            professional=prof, user_id=uid, share_assessments=True
        ).exists()
        if shared_cons:
            shared_assessments = list(
                Assessment.objects.filter(user_id=uid)
                .order_by('-created_at')[:3]
                .values('assessment_type', 'score', 'severity', 'created_at')
            )

        shared_mood = []
        shared_mood_cons = Consultation.objects.filter(
            professional=prof, user_id=uid, share_mood_logs=True
        ).exists()
        if shared_mood_cons:
            shared_mood = list(
                MoodLog.objects.filter(user_id=uid)
                .order_by('-logged_at')[:7]
                .values('mood', 'mood_score', 'energy_level', 'logged_at')
            )

        patients.append({
            'user_id': uid,
            'display_name': c['user__display_name'],
            'session_count': session_count,
            'last_session': last_session.scheduled_at.isoformat() if last_session else None,
            'shared_assessments': shared_assessments,
            'shared_mood_last7': shared_mood,
        })

    return Response(patients)


@api_view(['GET', 'POST'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_availability(request):
    prof = request.professional
    if request.method == 'GET':
        avail = ProfessionalAvailability.objects.filter(professional=prof)
        return Response(AvailabilitySerializer(avail, many=True).data)

    # POST — set full availability (replace all)
    slots = request.data.get('slots', [])
    ProfessionalAvailability.objects.filter(professional=prof).delete()
    created = []
    for slot in slots:
        a = ProfessionalAvailability.objects.create(
            professional=prof,
            day_of_week=slot['day_of_week'],
            start_time=slot['start_time'],
            end_time=slot['end_time'],
            is_active=slot.get('is_active', True),
        )
        created.append(a)
    return Response(AvailabilitySerializer(created, many=True).data)


@api_view(['PATCH'])
@permission_classes([IsAuthenticated])
@require_professional
def pro_update_profile(request):
    prof = request.professional
    allowed = ['bio', 'rate_per_hour', 'is_available_online',
                'is_accepting_new_patients', 'mpesa_number', 'years_experience']
    for field in allowed:
        if field in request.data:
            setattr(prof, field, request.data[field])
    prof.save()
    return Response(ProfessionalSerializer(prof).data)
