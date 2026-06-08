from django.contrib import admin
from .models import CrisisEvent


@admin.register(CrisisEvent)
class CrisisEventAdmin(admin.ModelAdmin):
    list_display = ['user', 'severity', 'trigger_source', 'resolved', 'created_at']
    list_filter = ['severity', 'resolved', 'trigger_source']
    list_editable = ['resolved']
    search_fields = ['user__username']
