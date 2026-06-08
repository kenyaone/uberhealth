from django.contrib import admin
from .models import Payment, ProfessionalPayout


@admin.register(Payment)
class PaymentAdmin(admin.ModelAdmin):
    list_display = ['consultation', 'amount', 'status', 'mpesa_transaction_id', 'created_at']
    list_filter = ['status']
    readonly_fields = ['mpesa_checkout_id', 'mpesa_transaction_id', 'mpesa_result_code']


@admin.register(ProfessionalPayout)
class ProfessionalPayoutAdmin(admin.ModelAdmin):
    list_display = ['professional', 'amount', 'status', 'paid_at']
    list_filter = ['status']
    list_editable = ['status']
