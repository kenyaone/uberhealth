from rest_framework import serializers
from .models import Plan, Subscription, SubscriptionPayment


class PlanSerializer(serializers.ModelSerializer):
    class Meta:
        model = Plan
        fields = ['id', 'name', 'tier', 'price_kes', 'interval', 'features',
                  'assessment_limit', 'lesson_limit']


class SubscriptionSerializer(serializers.ModelSerializer):
    plan = PlanSerializer(read_only=True)
    is_active = serializers.BooleanField(read_only=True)

    class Meta:
        model = Subscription
        fields = ['id', 'plan', 'status', 'started_at', 'expires_at',
                  'amount_paid', 'is_active', 'auto_renew']


class SubscribeSerializer(serializers.Serializer):
    plan_id = serializers.IntegerField()
    phone = serializers.CharField(max_length=15)
