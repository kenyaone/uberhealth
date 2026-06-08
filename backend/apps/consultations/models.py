import uuid
from django.db import models
from apps.accounts.models import User
from apps.professionals.models import Professional


def generate_consultation_id():
    return f"cons-{uuid.uuid4().hex[:8]}"


class Consultation(models.Model):
    STATUS_PENDING = 'pending'
    STATUS_CONFIRMED = 'confirmed'
    STATUS_IN_PROGRESS = 'in_progress'
    STATUS_COMPLETED = 'completed'
    STATUS_CANCELLED = 'cancelled'
    STATUS_REFUNDED = 'refunded'
    STATUS_CHOICES = [
        (STATUS_PENDING, 'Pending Payment'),
        (STATUS_CONFIRMED, 'Confirmed'),
        (STATUS_IN_PROGRESS, 'In Progress'),
        (STATUS_COMPLETED, 'Completed'),
        (STATUS_CANCELLED, 'Cancelled'),
        (STATUS_REFUNDED, 'Refunded'),
    ]

    consultation_id = models.CharField(max_length=20, unique=True, default=generate_consultation_id)
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='consultations')
    professional = models.ForeignKey(Professional, on_delete=models.CASCADE, related_name='consultations')
    scheduled_at = models.DateTimeField()
    duration_minutes = models.IntegerField(default=60)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default=STATUS_PENDING)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    jitsi_room = models.CharField(max_length=50, blank=True)
    share_assessments = models.BooleanField(default=False)
    share_mood_logs = models.BooleanField(default=False)
    recording_enabled = models.BooleanField(default=True)
    professional_notes = models.TextField(blank=True)
    user_rating = models.IntegerField(null=True, blank=True)
    user_review = models.TextField(blank=True)
    actual_start = models.DateTimeField(null=True, blank=True)
    actual_end = models.DateTimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'consultations'
        ordering = ['-scheduled_at']

    def __str__(self):
        return f"{self.consultation_id} — {self.user.display_name} with {self.professional}"

    def save(self, *args, **kwargs):
        if not self.jitsi_room:
            self.jitsi_room = self.consultation_id
        super().save(*args, **kwargs)
