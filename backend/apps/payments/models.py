from django.db import models
from apps.consultations.models import Consultation
from apps.professionals.models import Professional


class Payment(models.Model):
    STATUS_PENDING = 'pending'
    STATUS_COMPLETED = 'completed'
    STATUS_FAILED = 'failed'
    STATUS_REFUNDED = 'refunded'
    STATUS_CHOICES = [
        (STATUS_PENDING, 'Pending'),
        (STATUS_COMPLETED, 'Completed'),
        (STATUS_FAILED, 'Failed'),
        (STATUS_REFUNDED, 'Refunded'),
    ]

    consultation = models.OneToOneField(Consultation, on_delete=models.CASCADE, related_name='payment')
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    phone = models.CharField(max_length=15)
    mpesa_checkout_id = models.CharField(max_length=100, blank=True)
    mpesa_transaction_id = models.CharField(max_length=100, blank=True)
    mpesa_result_code = models.CharField(max_length=10, blank=True)
    mpesa_result_desc = models.TextField(blank=True)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default=STATUS_PENDING)
    created_at = models.DateTimeField(auto_now_add=True)
    completed_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'payments'

    def __str__(self):
        return f"{self.consultation.consultation_id} — KES {self.amount} — {self.status}"


class ProfessionalPayout(models.Model):
    STATUS_PENDING = 'pending'
    STATUS_PAID = 'paid'
    STATUS_CHOICES = [(STATUS_PENDING, 'Pending'), (STATUS_PAID, 'Paid')]

    professional = models.ForeignKey(Professional, on_delete=models.CASCADE, related_name='payouts')
    consultation = models.ForeignKey(Consultation, on_delete=models.CASCADE, related_name='payout')
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default=STATUS_PENDING)
    paid_at = models.DateTimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'professional_payouts'
