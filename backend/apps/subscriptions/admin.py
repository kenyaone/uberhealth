from django.contrib import admin
from .models import Plan, Subscription, SubscriptionPayment


@admin.register(Plan)
class PlanAdmin(admin.ModelAdmin):
    list_display = ['name', 'tier', 'price_kes', 'interval', 'assessment_limit', 'lesson_limit', 'is_active']
    list_editable = ['is_active']


@admin.register(Subscription)
class SubscriptionAdmin(admin.ModelAdmin):
    list_display = ['user', 'plan', 'status', 'expires_at', 'amount_paid']
    list_filter = ['status', 'plan__tier']
    search_fields = ['user__username']


admin.site.register(SubscriptionPayment)
