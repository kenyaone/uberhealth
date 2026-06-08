from django.contrib import admin
from .models import EAPTier, Company, EAPSubscription, EAPEmployee


@admin.register(EAPTier)
class EAPTierAdmin(admin.ModelAdmin):
    list_display = ['name', 'min_employees', 'max_employees', 'price_kes_annual', 'sessions_per_employee']


@admin.register(Company)
class CompanyAdmin(admin.ModelAdmin):
    list_display = ['name', 'contact_name', 'contact_email', 'employee_count', 'is_active', 'created_at']
    list_editable = ['is_active']


@admin.register(EAPSubscription)
class EAPSubscriptionAdmin(admin.ModelAdmin):
    list_display = ['company', 'tier', 'status', 'employee_limit', 'sessions_used', 'sessions_total', 'expires_at']
    list_editable = ['status']


admin.site.register(EAPEmployee)
