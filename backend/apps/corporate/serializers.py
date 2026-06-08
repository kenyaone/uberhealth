from rest_framework import serializers
from .models import EAPTier, Company, EAPSubscription, EAPEmployee


class EAPTierSerializer(serializers.ModelSerializer):
    class Meta:
        model = EAPTier
        fields = ['id', 'name', 'min_employees', 'max_employees',
                  'price_kes_annual', 'sessions_per_employee', 'features']


class CompanySerializer(serializers.ModelSerializer):
    class Meta:
        model = Company
        fields = ['id', 'name', 'contact_name', 'contact_email', 'contact_phone',
                  'industry', 'employee_count', 'kra_pin', 'address', 'is_active', 'created_at']
        read_only_fields = ['id', 'is_active', 'created_at']


class EAPSubscriptionSerializer(serializers.ModelSerializer):
    company = CompanySerializer(read_only=True)
    tier = EAPTierSerializer(read_only=True)
    sessions_remaining = serializers.IntegerField(read_only=True)
    is_active = serializers.BooleanField(read_only=True)

    class Meta:
        model = EAPSubscription
        fields = ['id', 'company', 'tier', 'status', 'employee_limit', 'sessions_used',
                  'sessions_total', 'sessions_remaining', 'amount_paid', 'started_at',
                  'expires_at', 'is_active']


class EAPApplicationSerializer(serializers.Serializer):
    company_name = serializers.CharField(max_length=200)
    contact_name = serializers.CharField(max_length=100)
    contact_email = serializers.EmailField()
    contact_phone = serializers.CharField(max_length=15)
    industry = serializers.CharField(max_length=100, required=False, allow_blank=True)
    employee_count = serializers.IntegerField(min_value=1)
    kra_pin = serializers.CharField(max_length=20, required=False, allow_blank=True)
    tier_id = serializers.IntegerField()
    phone = serializers.CharField(max_length=15, help_text='M-Pesa number for payment')
