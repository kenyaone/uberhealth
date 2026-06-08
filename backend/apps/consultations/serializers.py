from rest_framework import serializers
from .models import Consultation
from apps.professionals.serializers import ProfessionalSerializer
from apps.accounts.serializers import UserSerializer


class ConsultationSerializer(serializers.ModelSerializer):
    professional_detail = ProfessionalSerializer(source='professional', read_only=True)
    user_display_name = serializers.CharField(source='user.display_name', read_only=True)
    jitsi_url = serializers.SerializerMethodField()

    class Meta:
        model = Consultation
        fields = [
            'id', 'consultation_id', 'user_display_name', 'professional', 'professional_detail',
            'scheduled_at', 'duration_minutes', 'status', 'amount',
            'jitsi_room', 'jitsi_url', 'share_assessments', 'share_mood_logs',
            'recording_enabled', 'professional_notes', 'user_rating', 'user_review',
            'actual_start', 'actual_end', 'created_at',
        ]
        read_only_fields = ['id', 'consultation_id', 'status', 'professional_notes', 'actual_start', 'actual_end']

    def get_jitsi_url(self, obj):
        from django.conf import settings
        if obj.status in ['confirmed', 'in_progress']:
            return f"https://{settings.JITSI_DOMAIN}/{obj.jitsi_room}"
        return None


class BookConsultationSerializer(serializers.Serializer):
    professional_id = serializers.IntegerField()
    scheduled_at = serializers.DateTimeField()
    duration_minutes = serializers.IntegerField(default=60, min_value=30, max_value=120)
    share_assessments = serializers.BooleanField(default=False)
    share_mood_logs = serializers.BooleanField(default=False)
    recording_enabled = serializers.BooleanField(default=True)
    phone = serializers.CharField(max_length=15, help_text='M-Pesa phone number for payment')


class RateConsultationSerializer(serializers.Serializer):
    rating = serializers.IntegerField(min_value=1, max_value=5)
    review = serializers.CharField(max_length=1000, required=False, allow_blank=True)
