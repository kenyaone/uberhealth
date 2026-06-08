from django.db import models
from django.utils import timezone
from datetime import timedelta
from apps.accounts.models import User


class EAPTier(models.Model):
    name = models.CharField(max_length=50)            # Starter, Growth, Enterprise
    min_employees = models.IntegerField()
    max_employees = models.IntegerField()
    price_kes_annual = models.DecimalField(max_digits=12, decimal_places=2)
    sessions_per_employee = models.IntegerField(default=4)  # sessions covered per year
    features = models.JSONField(default=list)
    is_active = models.BooleanField(default=True)

    class Meta:
        db_table = 'eap_tiers'

    def __str__(self):
        return f"{self.name} ({self.min_employees}–{self.max_employees} staff)"


class Company(models.Model):
    name = models.CharField(max_length=200)
    contact_name = models.CharField(max_length=100)
    contact_email = models.EmailField()
    contact_phone = models.CharField(max_length=15)
    industry = models.CharField(max_length=100, blank=True)
    employee_count = models.IntegerField()
    kra_pin = models.CharField(max_length=20, blank=True)
    address = models.TextField(blank=True)
    is_active = models.BooleanField(default=False)  # activated after payment
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'companies'

    def __str__(self):
        return self.name


class EAPSubscription(models.Model):
    STATUS_PENDING = 'pending'
    STATUS_ACTIVE = 'active'
    STATUS_EXPIRED = 'expired'
    STATUS_CHOICES = [
        (STATUS_PENDING, 'Pending Payment'),
        (STATUS_ACTIVE, 'Active'),
        (STATUS_EXPIRED, 'Expired'),
    ]

    company = models.ForeignKey(Company, on_delete=models.CASCADE, related_name='subscriptions')
    tier = models.ForeignKey(EAPTier, on_delete=models.PROTECT)
    admin_user = models.ForeignKey(User, on_delete=models.SET_NULL, null=True, related_name='managed_companies')
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default=STATUS_PENDING)
    employee_limit = models.IntegerField()
    sessions_used = models.IntegerField(default=0)
    sessions_total = models.IntegerField()
    amount_paid = models.DecimalField(max_digits=12, decimal_places=2, default=0)
    started_at = models.DateTimeField(null=True)
    expires_at = models.DateTimeField(null=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'eap_subscriptions'

    def __str__(self):
        return f"{self.company.name} — {self.tier.name}"

    @property
    def is_active(self):
        return self.status == self.STATUS_ACTIVE and self.expires_at and self.expires_at > timezone.now()

    @property
    def sessions_remaining(self):
        return max(0, self.sessions_total - self.sessions_used)


class EAPEmployee(models.Model):
    eap_subscription = models.ForeignKey(EAPSubscription, on_delete=models.CASCADE, related_name='employees')
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='eap_memberships')
    sessions_used = models.IntegerField(default=0)
    sessions_allowed = models.IntegerField(default=4)
    is_active = models.BooleanField(default=True)
    joined_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'eap_employees'
        unique_together = ['eap_subscription', 'user']

    def __str__(self):
        return f"{self.user.display_name} @ {self.eap_subscription.company.name}"
