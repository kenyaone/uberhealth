from django.db import models
from apps.accounts.models import User


class Lesson(models.Model):
    CATEGORY_DEPRESSION = 'depression'
    CATEGORY_ANXIETY = 'anxiety'
    CATEGORY_ALCOHOL = 'alcohol'
    CATEGORY_GAMBLING = 'gambling'
    CATEGORY_TOBACCO = 'tobacco'
    CATEGORY_RELATIONSHIPS = 'relationships'
    CATEGORY_WELLNESS = 'wellness'
    CATEGORY_CHOICES = [
        (CATEGORY_DEPRESSION, 'Depression & Mood'),
        (CATEGORY_ANXIETY, 'Anxiety & Stress'),
        (CATEGORY_ALCOHOL, 'Alcohol Recovery'),
        (CATEGORY_GAMBLING, 'Gambling Recovery'),
        (CATEGORY_TOBACCO, 'Tobacco Cessation'),
        (CATEGORY_RELATIONSHIPS, 'Relationships & Family'),
        (CATEGORY_WELLNESS, 'General Wellness'),
    ]

    LANG_EN = 'en'
    LANG_SW = 'sw'
    LANG_CHOICES = [(LANG_EN, 'English'), (LANG_SW, 'Kiswahili')]

    LEVEL_BEGINNER = 'beginner'
    LEVEL_INTERMEDIATE = 'intermediate'
    LEVEL_ADVANCED = 'advanced'
    LEVEL_CHOICES = [
        (LEVEL_BEGINNER, 'Beginner'),
        (LEVEL_INTERMEDIATE, 'Intermediate'),
        (LEVEL_ADVANCED, 'Advanced'),
    ]

    title = models.CharField(max_length=200)
    title_sw = models.CharField(max_length=200, blank=True)
    slug = models.SlugField(unique=True)
    category = models.CharField(max_length=20, choices=CATEGORY_CHOICES)
    language = models.CharField(max_length=5, choices=LANG_CHOICES, default=LANG_EN)
    level = models.CharField(max_length=20, choices=LEVEL_CHOICES, default=LEVEL_BEGINNER)
    summary = models.TextField()
    summary_sw = models.TextField(blank=True)
    content = models.TextField()
    content_sw = models.TextField(blank=True)
    key_takeaways = models.JSONField(default=list)
    key_takeaways_sw = models.JSONField(default=list)
    duration_minutes = models.IntegerField(default=10)
    is_premium = models.BooleanField(default=False)
    is_published = models.BooleanField(default=True)
    order = models.IntegerField(default=0)
    thumbnail_emoji = models.CharField(max_length=10, default='📚')
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'lessons'
        ordering = ['category', 'order', 'id']

    def __str__(self):
        return self.title


class LessonProgress(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='lesson_progress')
    lesson = models.ForeignKey(Lesson, on_delete=models.CASCADE, related_name='progress')
    completed = models.BooleanField(default=False)
    progress_pct = models.IntegerField(default=0)
    started_at = models.DateTimeField(auto_now_add=True)
    completed_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'lesson_progress'
        unique_together = ['user', 'lesson']

    def __str__(self):
        return f"{self.user.username} — {self.lesson.title} — {self.progress_pct}%"
