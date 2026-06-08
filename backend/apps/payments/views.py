import json
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from rest_framework import status
from django.utils import timezone
from .models import Payment
from apps.consultations.models import Consultation


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
