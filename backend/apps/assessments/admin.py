from django.contrib import admin
from .models import Assessment


@admin.register(Assessment)
class AssessmentAdmin(admin.ModelAdmin):
    list_display = ['user', 'assessment_type', 'score', 'severity', 'is_crisis_flag', 'created_at']
    list_filter = ['assessment_type', 'severity', 'is_crisis_flag']
    search_fields = ['user__username', 'user__display_name']
    readonly_fields = ['score', 'severity', 'interpretation', 'recommendations', 'responses']
