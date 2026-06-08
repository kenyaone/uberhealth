from django.contrib import admin
from .models import Consultation


@admin.register(Consultation)
class ConsultationAdmin(admin.ModelAdmin):
    list_display = ['consultation_id', 'user', 'professional', 'scheduled_at', 'status', 'amount']
    list_filter = ['status']
    search_fields = ['consultation_id', 'user__display_name', 'professional__user__display_name']
    readonly_fields = ['consultation_id', 'jitsi_room']
