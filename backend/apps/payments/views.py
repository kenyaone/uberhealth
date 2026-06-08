import json
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from rest_framework import status
from django.utils import timezone
from django.db.models import Sum, Count
from .models import Payment, ProfessionalPayout
from apps.consultations.models import Consultation
from .b2c import send_b2c_payment


@api_view(['POST'])
@permission_classes([AllowAny])
def mpesa_callback(request):
    try:
        body = request.data.get('Body', {})
        callback = body.get('stkCallback', {})
        checkout_id = callback.get('CheckoutRequestID')
        result_code = str(callback.get('ResultCode', '1'))
        result_desc = callback.get('ResultDesc', '')

        try:
            payment = Payment.objects.get(mpesa_checkout_id=checkout_id)
        except Payment.DoesNotExist:
            return Response({'ResultCode': 0, 'ResultDesc': 'OK'})

        payment.mpesa_result_code = result_code
        payment.mpesa_result_desc = result_desc

        if result_code == '0':
            items = callback.get('CallbackMetadata', {}).get('Item', [])
            for item in items:
                if item.get('Name') == 'MpesaReceiptNumber':
                    payment.mpesa_transaction_id = item.get('Value', '')
                    break
            payment.status = Payment.STATUS_COMPLETED
            payment.completed_at = timezone.now()
            payment.phone = ''  # delete phone after payment
            payment.save()

            consultation = payment.consultation
            consultation.status = Consultation.STATUS_CONFIRMED
            consultation.save()
        else:
            payment.status = Payment.STATUS_FAILED
            payment.save()

    except Exception:
        pass

    return Response({'ResultCode': 0, 'ResultDesc': 'Accepted'})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def payment_status(request, consultation_id):
    try:
        consultation = Consultation.objects.get(
            consultation_id=consultation_id,
            user=request.user,
        )
        payment = consultation.payment
        return Response({
            'status': payment.status,
            'consultation_status': consultation.status,
            'amount': str(payment.amount),
            'transaction_id': payment.mpesa_transaction_id,
        })
    except (Consultation.DoesNotExist, Payment.DoesNotExist):
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def trigger_payout(request, payout_id):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)
    try:
        payout = ProfessionalPayout.objects.select_related('professional__user').get(pk=payout_id)
    except ProfessionalPayout.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)
    if payout.status == ProfessionalPayout.STATUS_PAID:
        return Response({'error': 'Already paid.'}, status=status.HTTP_400_BAD_REQUEST)

    phone = payout.professional.mpesa_number
    if not phone:
        return Response({'error': 'Professional has no M-Pesa number.'}, status=status.HTTP_400_BAD_REQUEST)

    result = send_b2c_payment(
        phone=phone,
        amount=int(payout.amount),
        reference=f'PAYOUT-{payout.id}',
        remarks=f'Session payout — {payout.consultation.consultation_id}',
    )
    payout.status = ProfessionalPayout.STATUS_PAID
    payout.paid_at = timezone.now()
    payout.save()

    return Response({'message': f'KES {int(payout.amount):,} sent to {phone}.', 'result': result})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def revenue_dashboard(request):
    if request.user.role != 'admin':
        return Response({'error': 'Admin only.'}, status=status.HTTP_403_FORBIDDEN)

    completed = Payment.objects.filter(status=Payment.STATUS_COMPLETED)
    total_revenue = completed.aggregate(t=Sum('amount'))['t'] or 0
    session_count = completed.count()
    platform_revenue = float(total_revenue) * 0.20
    professional_payouts = float(total_revenue) * 0.80

    pending_payouts = ProfessionalPayout.objects.filter(status=ProfessionalPayout.STATUS_PENDING)
    pending_payout_total = pending_payouts.aggregate(t=Sum('amount'))['t'] or 0

    from apps.subscriptions.models import Subscription
    active_subs = Subscription.objects.filter(
        status=Subscription.STATUS_ACTIVE, expires_at__gt=timezone.now()
    )
    sub_revenue = active_subs.aggregate(t=Sum('amount_paid'))['t'] or 0

    from apps.corporate.models import EAPSubscription
    active_eap = EAPSubscription.objects.filter(status=EAPSubscription.STATUS_ACTIVE)
    eap_revenue = active_eap.aggregate(t=Sum('amount_paid'))['t'] or 0

    return Response({
        'sessions': {
            'count': session_count,
            'total_revenue': float(total_revenue),
            'platform_share': platform_revenue,
            'professional_share': professional_payouts,
        },
        'payouts': {
            'pending_count': pending_payouts.count(),
            'pending_total': float(pending_payout_total),
        },
        'subscriptions': {
            'active_count': active_subs.count(),
            'revenue': float(sub_revenue),
        },
        'corporate_eap': {
            'active_count': active_eap.count(),
            'revenue': float(eap_revenue),
        },
        'total_platform_revenue': platform_revenue + float(sub_revenue) + float(eap_revenue),
    })
