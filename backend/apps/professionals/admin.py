from django.contrib import admin
from .models import Professional, Specialization, Language, ProfessionalAvailability


@admin.register(Professional)
class ProfessionalAdmin(admin.ModelAdmin):
    list_display = ['user', 'kmpdc_license', 'verification_status', 'rating', 'total_sessions', 'rate_per_hour']
    list_filter = ['verification_status', 'gender', 'is_available_online']
    list_editable = ['verification_status']
    search_fields = ['user__display_name', 'kmpdc_license']
    filter_horizontal = ['specializations', 'languages']


@admin.register(Specialization)
class SpecializationAdmin(admin.ModelAdmin):
    prepopulated_fields = {'slug': ('name',)}


admin.site.register(Language)
admin.site.register(ProfessionalAvailability)
