from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from .models import User


@admin.register(User)
class UserAdmin(BaseUserAdmin):
    list_display = ['username', 'display_name', 'email', 'role', 'is_active', 'created_at']
    list_filter = ['role', 'is_active', 'is_anonymous_mode']
    fieldsets = BaseUserAdmin.fieldsets + (
        ('MHAP Fields', {'fields': ('display_name', 'phone', 'role', 'is_anonymous_mode', 'avatar')}),
    )
    search_fields = ['username', 'display_name', 'email']
