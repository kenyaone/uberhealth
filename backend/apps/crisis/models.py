from django.db import models
from apps.accounts.models import User


class CrisisEvent(models.Model):
    SEVERITY_LOW = 'low'
    SEVERITY_MEDIUM = 'medium'
    SEVERITY_HIGH = 'high'
    SEVERITY_CRITICAL = 'critical'
    SEVERITY_CHOICES = [
        (SEVERITY_LOW, 'Low'),
        (SEVERITY_MEDIUM, 'Medium'),
        (SEVERITY_HIGH, 'High'),
        (SEVERITY_CRITICAL, 'Critical'),
    ]

    SOURCE_ASSESSMENT = 'assessment'
    SOURCE_MOOD_LOG = 'mood_log'
    SOURCE_CRAVING_LOG = 'craving_log'
    SOURCE_CHOICES = [
        (SOURCE_ASSESSMENT, 'Assessment'),
        (SOURCE_MOOD_LOG, 'Mood Log'),
        (SOURCE_CRAVING_LOG, 'Craving Log'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='crisis_events')
    trigger_source = models.CharField(max_length=20, choices=SOURCE_CHOICES)
    content = models.TextField()
    severity = models.CharField(max_length=20, choices=SEVERITY_CHOICES)
    keywords_detected = models.JSONField(default=list)
    response_action = models.CharField(max_length=100, blank=True)
    resolved = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'crisis_events'
        ordering = ['-created_at']

    def __str__(self):
        return f"{self.user.username} — {self.severity} — {self.created_at.date()}"
