from django.db import models
from apps.accounts.models import User


class Assessment(models.Model):
    TYPE_PHQ9 = 'phq9'
    TYPE_GAD7 = 'gad7'
    TYPE_AUDIT = 'audit'
    TYPE_PGSI = 'pgsi'
    TYPE_FTND = 'ftnd'
    TYPE_CHOICES = [
        (TYPE_PHQ9, 'PHQ-9 (Depression)'),
        (TYPE_GAD7, 'GAD-7 (Anxiety)'),
        (TYPE_AUDIT, 'AUDIT (Alcohol)'),
        (TYPE_PGSI, 'PGSI (Gambling)'),
        (TYPE_FTND, 'FTND (Nicotine)'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='assessments')
    assessment_type = models.CharField(max_length=10, choices=TYPE_CHOICES)
    score = models.IntegerField()
    severity = models.CharField(max_length=30)
    interpretation = models.TextField()
    recommendations = models.TextField()
    responses = models.JSONField(default=dict)
    is_crisis_flag = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'assessments'
        ordering = ['-created_at']

    def __str__(self):
        return f"{self.user.username} - {self.assessment_type} - Score: {self.score}"
