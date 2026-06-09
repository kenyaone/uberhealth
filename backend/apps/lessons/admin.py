from django.contrib import admin
from .models import Lesson, LessonProgress


@admin.register(Lesson)
class LessonAdmin(admin.ModelAdmin):
    list_display = ['title', 'category', 'level', 'is_premium', 'is_published', 'duration_minutes', 'order']
    list_filter = ['category', 'level', 'is_premium', 'is_published', 'language']
    list_editable = ['is_premium', 'is_published', 'order']
    prepopulated_fields = {'slug': ('title',)}
    search_fields = ['title', 'summary']


admin.site.register(LessonProgress)
