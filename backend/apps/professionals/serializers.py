from rest_framework import serializers
from .models import Professional, Specialization, Language, ProfessionalAvailability
from apps.accounts.serializers import UserSerializer


class SpecializationSerializer(serializers.ModelSerializer):
    class Meta:
        model = Specialization
        fields = ['id', 'name', 'slug']


class LanguageSerializer(serializers.ModelSerializer):
    class Meta:
        model = Language
        fields = ['id', 'name']


class AvailabilitySerializer(serializers.ModelSerializer):
    day_name = serializers.SerializerMethodField()

    class Meta:
        model = ProfessionalAvailability
        fields = ['id', 'day_of_week', 'day_name', 'start_time', 'end_time', 'is_active']

    def get_day_name(self, obj):
        return obj.get_day_of_week_display()


class ProfessionalSerializer(serializers.ModelSerializer):
    user = UserSerializer(read_only=True)
    specializations = SpecializationSerializer(many=True, read_only=True)
    languages = LanguageSerializer(many=True, read_only=True)
    availability = AvailabilitySerializer(many=True, read_only=True)
    display_name = serializers.CharField(source='user.display_name', read_only=True)

    class Meta:
        model = Professional
        fields = [
            'id', 'user', 'display_name', 'kmpdc_license', 'verification_status',
            'specializations', 'languages', 'rate_per_hour', 'bio', 'years_experience',
            'gender', 'rating', 'total_sessions', 'total_reviews',
            'is_available_online', 'is_accepting_new_patients',
            'profile_photo', 'availability', 'created_at',
        ]
        read_only_fields = ['id', 'rating', 'total_sessions', 'total_reviews', 'verification_status']


class ProfessionalRegisterSerializer(serializers.ModelSerializer):
    specialization_ids = serializers.ListField(child=serializers.IntegerField(), write_only=True, required=False)
    language_ids = serializers.ListField(child=serializers.IntegerField(), write_only=True, required=False)

    class Meta:
        model = Professional
        fields = [
            'kmpdc_license', 'rate_per_hour', 'bio', 'years_experience',
            'gender', 'is_available_online', 'profile_photo', 'mpesa_number',
            'specialization_ids', 'language_ids',
        ]

    def create(self, validated_data):
        specialization_ids = validated_data.pop('specialization_ids', [])
        language_ids = validated_data.pop('language_ids', [])
        professional = Professional.objects.create(**validated_data)
        if specialization_ids:
            professional.specializations.set(specialization_ids)
        if language_ids:
            professional.languages.set(language_ids)
        return professional
