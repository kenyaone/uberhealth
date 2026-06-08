from rest_framework import status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated, AllowAny
from rest_framework.response import Response
from django.utils import timezone
from datetime import timedelta
from .models import EAPTier, Company, EAPSubscription, EAPEmployee
from .serializers import EAPTierSerializer, EAPSubscriptionSerializer, EAPApplicationSerializer
from apps.payments.mpesa import initiate_stk_push


@api_view(['GET'])
@permission_classes([AllowAny])
def eap_tiers(request):
    tiers = EAPTier.objects.filter(is_active=True).order_by('price_kes_annual')
    return Response(EAPTierSerializer(tiers, many=True).data)


@api_view(['POST'])
@permission_classes([AllowAny])
def apply_eap(request):
    serializer = EAPApplicationSerializer(data=request.data)
    if not serializer.is_valid():
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
    data = serializer.validated_data

    try:
        tier = EAPTier.objects.get(id=data['tier_id'], is_active=True)
    except EAPTier.DoesNotExist:
        return Response({'error': 'EAP tier not found.'}, status=status.HTTP_404_NOT_FOUND)

    company = Company.objects.create(
        name=data['company_name'],
        contact_name=data['contact_name'],
        contact_email=data['contact_email'],
        contact_phone=data['contact_phone'],
        industry=data.get('industry', ''),
        employee_count=data['employee_count'],
        kra_pin=data.get('kra_pin', ''),
    )

    sessions_total = data['employee_count'] * tier.sessions_per_employee
    eap_sub = EAPSubscription.objects.create(
        company=company,
        tier=tier,
        employee_limit=min(data['employee_count'], tier.max_employees),
        sessions_total=sessions_total,
        amount_paid=tier.price_kes_annual,
        status=EAPSubscription.STATUS_PENDING,
    )

    mpesa_result = initiate_stk_push(
        phone=data['phone'],
        amount=int(tier.price_kes_annual),
        reference=f'EAP-{eap_sub.id}',
        description=f'MHAP EAP {tier.name} — {company.name}',
    )

    return Response({
        'company': {'id': company.id, 'name': company.name},
        'eap_subscription_id': eap_sub.id,
        'tier': EAPTierSerializer(tier).data,
        'sessions_total': sessions_total,
        'payment': {
            'checkout_id': mpesa_result.get('CheckoutRequestID'),
            'message': f'Check your phone. Pay KES {int(tier.price_kes_annual):,} for {tier.name} EAP package.',
            'amount': int(tier.price_kes_annual),
        }
    }, status=status.HTTP_201_CREATED)


@api_view(['POST'])
@permission_classes([AllowAny])
def eap_mpesa_callback(request):
    try:
        body = request.data.get('Body', {})
        callback = body.get('stkCallback', {})
        checkout_id = callback.get('CheckoutRequestID', '')
        result_code = str(callback.get('ResultCode', '1'))
        # Match by checkout ID prefix in reference — simplified for now
        if result_code == '0':
            # Admin activates manually after verifying — or auto-activate
            pass
    except Exception:
        pass
    return Response({'ResultCode': 0, 'ResultDesc': 'Accepted'})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def my_eap(request):
    employee = EAPEmployee.objects.filter(
        user=request.user, is_active=True
    ).select_related('eap_subscription__company', 'eap_subscription__tier').first()
    if not employee:
        return Response({'eap': None})
    sub = employee.eap_subscription
    return Response({
        'company': sub.company.name,
        'tier': sub.tier.name,
        'sessions_used': employee.sessions_used,
        'sessions_allowed': employee.sessions_allowed,
        'sessions_remaining': employee.sessions_allowed - employee.sessions_used,
        'eap_active': sub.is_active,
    })


# Admin
@api_view(['GET'])
@permission_classes([IsAuthenticated])
def admin_eap_list(request):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    subs = EAPSubscription.objects.select_related('company', 'tier').order_by('-created_at')
    return Response(EAPSubscriptionSerializer(subs, many=True).data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def admin_activate_eap(request, pk):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    try:
        sub = EAPSubscription.objects.get(pk=pk)
    except EAPSubscription.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)
    sub.status = EAPSubscription.STATUS_ACTIVE
    sub.started_at = timezone.now()
    sub.expires_at = timezone.now() + timedelta(days=365)
    sub.save()
    sub.company.is_active = True
    sub.company.save()
    return Response({'message': f'{sub.company.name} EAP activated.', 'expires_at': sub.expires_at})
