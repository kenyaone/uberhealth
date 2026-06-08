from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from django.utils import timezone
from django.db.models import Avg
from .models import Consultation
from .serializers import ConsultationSerializer, BookConsultationSerializer, RateConsultationSerializer
from apps.professionals.models import Professional
from apps.payments.mpesa import initiate_stk_push
from apps.payments.models import Payment, ProfessionalPayout
from django.conf import settings


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def book_consultation(request):
    serializer = BookConsultationSerializer(data=request.data)
    if not serializer.is_valid():
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    data = serializer.validated_data
    try:
        professional = Professional.objects.get(id=data['professional_id'], verification_status='verified')
    except Professional.DoesNotExist:
        return Response({'error': 'Professional not found.'}, status=status.HTTP_404_NOT_FOUND)

    consultation = Consultation.objects.create(
        user=request.user,
        professional=professional,
        scheduled_at=data['scheduled_at'],
        duration_minutes=data['duration_minutes'],
        amount=professional.rate_per_hour,
        share_assessments=data['share_assessments'],
        share_mood_logs=data['share_mood_logs'],
        recording_enabled=data['recording_enabled'],
        status=Consultation.STATUS_PENDING,
    )

    phone = data['phone']
    mpesa_result = initiate_stk_push(
        phone=phone,
        amount=int(professional.rate_per_hour),
        reference=consultation.consultation_id,
        description=f'MHAP Consultation - {consultation.consultation_id}',
    )

    Payment.objects.create(
        consultation=consultation,
        amount=professional.rate_per_hour,
        phone=phone,
        mpesa_checkout_id=mpesa_result.get('CheckoutRequestID', ''),
        status=Payment.STATUS_PENDING,
    )

    return Response({
        'consultation': ConsultationSerializer(consultation).data,
        'payment': {
            'checkout_id': mpesa_result.get('CheckoutRequestID'),
            'message': 'Check your phone for M-Pesa payment request.',
            'amount': int(professional.rate_per_hour),
        }
    }, status=status.HTTP_201_CREATED)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def my_consultations(request):
    consultations = Consultation.objects.filter(user=request.user).select_related('professional__user')
    return Response(ConsultationSerializer(consultations, many=True).data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def consultation_detail(request, consultation_id):
    try:
        consultation = Consultation.objects.get(
            consultation_id=consultation_id,
            user=request.user,
        )
    except Consultation.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)
    return Response(ConsultationSerializer(consultation).data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def rate_consultation(request, consultation_id):
    try:
        consultation = Consultation.objects.get(
            consultation_id=consultation_id,
            user=request.user,
            status=Consultation.STATUS_COMPLETED,
        )
    except Consultation.DoesNotExist:
        return Response({'error': 'Consultation not found or not completed.'}, status=status.HTTP_404_NOT_FOUND)

    if consultation.user_rating:
        return Response({'error': 'Already rated.'}, status=status.HTTP_400_BAD_REQUEST)

    serializer = RateConsultationSerializer(data=request.data)
    if not serializer.is_valid():
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    consultation.user_rating = serializer.validated_data['rating']
    consultation.user_review = serializer.validated_data.get('review', '')
    consultation.save()

    professional = consultation.professional
    avg = Consultation.objects.filter(
        professional=professional,
        status=Consultation.STATUS_COMPLETED,
        user_rating__isnull=False,
    ).aggregate(avg=Avg('user_rating'))
    professional.rating = round(avg['avg'] or 0, 2)
    professional.total_reviews = Consultation.objects.filter(
        professional=professional, user_rating__isnull=False
    ).count()
    professional.save()

    return Response({'message': 'Thank you for your feedback!'})


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def join_consultation(request, consultation_id):
    try:
        consultation = Consultation.objects.get(
            consultation_id=consultation_id,
            user=request.user,
            status__in=[Consultation.STATUS_CONFIRMED, Consultation.STATUS_IN_PROGRESS],
        )
    except Consultation.DoesNotExist:
        return Response({'error': 'Session not found or not ready.'}, status=status.HTTP_404_NOT_FOUND)

    jitsi_url = f"https://{settings.JITSI_DOMAIN}/{consultation.jitsi_room}"
    display_name = request.user.display_name

    if consultation.status == Consultation.STATUS_CONFIRMED:
        consultation.status = Consultation.STATUS_IN_PROGRESS
        consultation.actual_start = timezone.now()
        consultation.save()

    return Response({
        'jitsi_url': jitsi_url,
        'room': consultation.jitsi_room,
        'display_name': display_name,
        'consultation': ConsultationSerializer(consultation).data,
    })


# Professional-side views
@api_view(['GET'])
@permission_classes([IsAuthenticated])
def professional_consultations(request):
    if not request.user.is_professional:
        return Response({'error': 'Not a professional account.'}, status=status.HTTP_403_FORBIDDEN)
    consultations = Consultation.objects.filter(
        professional__user=request.user
    ).select_related('user')
    return Response(ConsultationSerializer(consultations, many=True).data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def add_professional_notes(request, consultation_id):
    if not request.user.is_professional:
        return Response({'error': 'Not a professional account.'}, status=status.HTTP_403_FORBIDDEN)
    try:
        consultation = Consultation.objects.get(
            consultation_id=consultation_id,
            professional__user=request.user,
        )
    except Consultation.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)

    consultation.professional_notes = request.data.get('notes', '')
    if request.data.get('mark_complete') and consultation.status == Consultation.STATUS_IN_PROGRESS:
        consultation.status = Consultation.STATUS_COMPLETED
        consultation.actual_end = timezone.now()
        professional = consultation.professional
        professional.total_sessions += 1
        professional.save()
        payout_amount = consultation.amount * (1 - settings.PLATFORM_COMMISSION_RATE)
        ProfessionalPayout.objects.get_or_create(
            consultation=consultation,
            defaults={'professional': professional, 'amount': payout_amount},
        )
    consultation.save()
    return Response(ConsultationSerializer(consultation).data)
