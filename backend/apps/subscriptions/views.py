from rest_framework import status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated, AllowAny
from rest_framework.response import Response
from django.utils import timezone
from .models import Plan, Subscription, SubscriptionPayment
from .serializers import PlanSerializer, SubscriptionSerializer, SubscribeSerializer
from apps.payments.mpesa import initiate_stk_push


@api_view(['GET'])
@permission_classes([AllowAny])
def plans(request):
    all_plans = Plan.objects.filter(is_active=True).order_by('price_kes')
    return Response(PlanSerializer(all_plans, many=True).data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def my_subscription(request):
    sub = Subscription.objects.filter(
        user=request.user,
        status=Subscription.STATUS_ACTIVE,
        expires_at__gt=timezone.now(),
    ).select_related('plan').first()
    if not sub:
        free_plan = Plan.objects.filter(tier='free').first()
        return Response({
            'tier': 'free',
            'plan': PlanSerializer(free_plan).data if free_plan else None,
            'is_active': True,
            'expires_at': None,
        })
    return Response(SubscriptionSerializer(sub).data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def subscribe(request):
    serializer = SubscribeSerializer(data=request.data)
    if not serializer.is_valid():
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    try:
        plan = Plan.objects.get(id=serializer.validated_data['plan_id'], is_active=True)
    except Plan.DoesNotExist:
        return Response({'error': 'Plan not found.'}, status=status.HTTP_404_NOT_FOUND)

    phone = serializer.validated_data['phone']
    sub = Subscription.objects.create(
        user=request.user,
        plan=plan,
        status=Subscription.STATUS_ACTIVE,
        amount_paid=plan.price_kes,
    )

    mpesa_result = initiate_stk_push(
        phone=phone,
        amount=int(plan.price_kes),
        reference=f'SUB-{sub.id}',
        description=f'MHAP {plan.name} Subscription',
    )

    SubscriptionPayment.objects.create(
        subscription=sub,
        amount=plan.price_kes,
        phone=phone,
        mpesa_checkout_id=mpesa_result.get('CheckoutRequestID', ''),
        status='pending',
    )

    return Response({
        'subscription': SubscriptionSerializer(sub).data,
        'payment': {
            'checkout_id': mpesa_result.get('CheckoutRequestID'),
            'message': f'Check your phone. Pay KES {int(plan.price_kes)} for {plan.name}.',
            'amount': int(plan.price_kes),
        }
    }, status=status.HTTP_201_CREATED)


@api_view(['POST'])
@permission_classes([AllowAny])
def subscription_mpesa_callback(request):
    try:
        body = request.data.get('Body', {})
        callback = body.get('stkCallback', {})
        checkout_id = callback.get('CheckoutRequestID')
        result_code = str(callback.get('ResultCode', '1'))

        payment = SubscriptionPayment.objects.get(mpesa_checkout_id=checkout_id)
        if result_code == '0':
            items = callback.get('CallbackMetadata', {}).get('Item', [])
            for item in items:
                if item.get('Name') == 'MpesaReceiptNumber':
                    payment.mpesa_transaction_id = item.get('Value', '')
                    payment.subscription.mpesa_transaction_id = item.get('Value', '')
                    payment.subscription.save()
                    break
            payment.status = 'completed'
            payment.phone = ''
        else:
            payment.status = 'failed'
            payment.subscription.status = Subscription.STATUS_CANCELLED
            payment.subscription.save()
        payment.save()
    except Exception:
        pass
    return Response({'ResultCode': 0, 'ResultDesc': 'Accepted'})


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def check_feature_access(request):
    feature = request.query_params.get('feature')
    user = request.user
    sub = Subscription.objects.filter(
        user=user, status=Subscription.STATUS_ACTIVE, expires_at__gt=timezone.now()
    ).select_related('plan').first()

    tier = sub.plan.tier if sub else 'free'
    plan = sub.plan if sub else Plan.objects.filter(tier='free').first()

    limits = {
        'tier': tier,
        'assessment_limit': plan.assessment_limit if plan else 3,
        'lesson_limit': plan.lesson_limit if plan else 8,
        'has_premium': tier in ['premium', 'pro'],
        'has_pro': tier == 'pro',
    }
    return Response(limits)
