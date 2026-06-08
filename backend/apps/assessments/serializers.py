from rest_framework import serializers
from .models import Assessment


class AssessmentSerializer(serializers.ModelSerializer):
    assessment_type_display = serializers.CharField(source='get_assessment_type_display', read_only=True)

    class Meta:
        model = Assessment
        fields = [
            'id', 'assessment_type', 'assessment_type_display', 'score',
            'severity', 'interpretation', 'recommendations',
            'responses', 'is_crisis_flag', 'created_at',
        ]
        read_only_fields = ['id', 'score', 'severity', 'interpretation', 'recommendations', 'is_crisis_flag', 'created_at']


class AssessmentSubmitSerializer(serializers.Serializer):
    assessment_type = serializers.ChoiceField(choices=['phq9', 'gad7', 'audit', 'pgsi', 'ftnd'])
    responses = serializers.DictField(child=serializers.IntegerField(min_value=0, max_value=4))
