from rest_framework import serializers
from .models import MoodLog, CravingLog, SobrietyTracker


class MoodLogSerializer(serializers.ModelSerializer):
    class Meta:
        model = MoodLog
        fields = ['id', 'mood', 'mood_score', 'energy_level', 'sleep_quality',
                  'triggers', 'coping_strategy', 'notes', 'logged_at', 'created_at']
        read_only_fields = ['id', 'created_at']


class CravingLogSerializer(serializers.ModelSerializer):
    class Meta:
        model = CravingLog
        fields = ['id', 'substance', 'intensity', 'duration_minutes',
                  'trigger', 'coping_strategy', 'resisted', 'notes', 'logged_at', 'created_at']
        read_only_fields = ['id', 'created_at']


class SobrietyTrackerSerializer(serializers.ModelSerializer):
    class Meta:
        model = SobrietyTracker
        fields = ['id', 'substance', 'start_date', 'current_streak',
                  'longest_streak', 'total_relapses', 'is_active', 'created_at', 'updated_at']
        read_only_fields = ['id', 'current_streak', 'longest_streak', 'total_relapses', 'created_at', 'updated_at']
