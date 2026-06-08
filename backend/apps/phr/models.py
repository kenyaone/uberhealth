from django.db import models
from apps.accounts.models import User


class MoodLog(models.Model):
    MOOD_EXCELLENT = 'excellent'
    MOOD_GOOD = 'good'
    MOOD_NEUTRAL = 'neutral'
    MOOD_SAD = 'sad'
    MOOD_TERRIBLE = 'terrible'
    MOOD_CHOICES = [
        (MOOD_EXCELLENT, 'Excellent 😄'),
        (MOOD_GOOD, 'Good 🙂'),
        (MOOD_NEUTRAL, 'Neutral 😐'),
        (MOOD_SAD, 'Sad 😢'),
        (MOOD_TERRIBLE, 'Terrible 😣'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='mood_logs')
    mood = models.CharField(max_length=20, choices=MOOD_CHOICES)
    mood_score = models.IntegerField()
    energy_level = models.IntegerField()
    sleep_quality = models.IntegerField()
    triggers = models.TextField(blank=True)
    coping_strategy = models.TextField(blank=True)
    notes = models.TextField(blank=True)
    logged_at = models.DateTimeField()
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'mood_logs'
        ordering = ['-logged_at']

    def __str__(self):
        return f"{self.user.username} — {self.mood} on {self.logged_at.date()}"


class CravingLog(models.Model):
    SUBSTANCE_ALCOHOL = 'alcohol'
    SUBSTANCE_GAMBLING = 'gambling'
    SUBSTANCE_TOBACCO = 'tobacco'
    SUBSTANCE_CANNABIS = 'cannabis'
    SUBSTANCE_MIRAA = 'miraa'
    SUBSTANCE_OTHER = 'other'
    SUBSTANCE_CHOICES = [
        (SUBSTANCE_ALCOHOL, 'Alcohol'),
        (SUBSTANCE_GAMBLING, 'Gambling'),
        (SUBSTANCE_TOBACCO, 'Tobacco'),
        (SUBSTANCE_CANNABIS, 'Cannabis'),
        (SUBSTANCE_MIRAA, 'Miraa'),
        (SUBSTANCE_OTHER, 'Other'),
    ]

    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='craving_logs')
    substance = models.CharField(max_length=20, choices=SUBSTANCE_CHOICES)
    intensity = models.IntegerField()
    duration_minutes = models.IntegerField(null=True, blank=True)
    trigger = models.TextField(blank=True)
    coping_strategy = models.TextField(blank=True)
    resisted = models.BooleanField(default=True)
    notes = models.TextField(blank=True)
    logged_at = models.DateTimeField()
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'craving_logs'
        ordering = ['-logged_at']


class SobrietyTracker(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='sobriety_trackers')
    substance = models.CharField(max_length=20, choices=CravingLog.SUBSTANCE_CHOICES)
    start_date = models.DateField()
    current_streak = models.IntegerField(default=0)
    longest_streak = models.IntegerField(default=0)
    total_relapses = models.IntegerField(default=0)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'sobriety_trackers'
        unique_together = ['user', 'substance']

    def __str__(self):
        return f"{self.user.username} — {self.substance} — {self.current_streak} days"
