from django.db import models
from django.utils import timezone
from datetime import timedelta
from apps.accounts.models import User


class Plan(models.Model):
    TIER_FREE = 'free'
    TIER_PREMIUM = 'premium'
    TIER_PRO = 'pro'           # for professionals
    TIER_CHOICES = [
        (TIER_FREE, 'Free'),
        (TIER_PREMIUM, 'Premium'),
        (TIER_PRO, 'Professional Pro'),
    ]
    INTERVAL_MONTHLY = 'monthly'
    INTERVAL_ANNUAL = 'annual'
    INTERVAL_CHOICES = [(INTERVAL_MONTHLY, 'Monthly'), (INTERVAL_ANNUAL, 'Annual')]

    name = models.CharField(max_length=50)
    tier = models.CharField(max_length=20, choices=TIER_CHOICES)
    price_kes = models.DecimalField(max_digits=10, decimal_places=2)
    interval = models.CharField(max_length=10, choices=INTERVAL_CHOICES, default=INTERVAL_MONTHLY)
    features = models.JSONField(default=list)
    assessment_limit = models.IntegerField(default=3, help_text='Per month. -1 = unlimited')
    lesson_limit = models.IntegerField(default=8, help_text='Per month. -1 = unlimited')
    is_active = models.BooleanField(default=True)

    class Meta:
        db_table = 'plans'

    def __str__(self):
        return f"{self.name} — KES {self.price_kes}/{self.interval}"


class Subscription(models.Model):
    STATUS_ACTIVE = 'active'
    STATUS_EXPIRED = 'expired'
    STATUS_CANCELLED = 'cancelled'
    STATUS_CHOICES = [
        (STATUS_ACTIVE, 'Active'),
        (STATUS_EXPIRED, 'Expired'),
        (STATUS_CANCELLED, 'Cancelled'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='subscriptions')
    plan = models.ForeignKey(Plan, on_delete=models.PROTECT)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default=STATUS_ACTIVE)
    started_at = models.DateTimeField(auto_now_add=True)
    expires_at = models.DateTimeField()
    mpesa_transaction_id = models.CharField(max_length=100, blank=True)
    amount_paid = models.DecimalField(max_digits=10, decimal_places=2)
    auto_renew = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'subscriptions'
        ordering = ['-created_at']

    def __str__(self):
        return f"{self.user.username} — {self.plan.name} — {self.status}"

    @property
    def is_active(self):
        return self.status == self.STATUS_ACTIVE and self.expires_at > timezone.now()

    def save(self, *args, **kwargs):
        if not self.expires_at:
            days = 30 if self.plan.interval == 'monthly' else 365
            self.expires_at = timezone.now() + timedelta(days=days)
        super().save(*args, **kwargs)


class SubscriptionPayment(models.Model):
    subscription = models.ForeignKey(Subscription, on_delete=models.CASCADE, related_name='payments')
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    phone = models.CharField(max_length=15)
    mpesa_checkout_id = models.CharField(max_length=100, blank=True)
    mpesa_transaction_id = models.CharField(max_length=100, blank=True)
    status = models.CharField(max_length=20, default='pending')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'subscription_payments'
