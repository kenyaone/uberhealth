from rest_framework import serializers
from django.contrib.auth.password_validation import validate_password
from .models import User


class SignupSerializer(serializers.ModelSerializer):
    password = serializers.CharField(write_only=True, validators=[validate_password])
    password_confirm = serializers.CharField(write_only=True)

    class Meta:
        model = User
        fields = ['username', 'display_name', 'password', 'password_confirm', 'email', 'phone', 'role']
        extra_kwargs = {
            'email': {'required': False},
            'phone': {'required': False},
        }

    def validate(self, attrs):
        if attrs['password'] != attrs.pop('password_confirm'):
            raise serializers.ValidationError({'password': 'Passwords do not match.'})
        # Convert empty strings to None for optional unique fields
        if not attrs.get('email'):
            attrs['email'] = None
        if not attrs.get('phone'):
            attrs['phone'] = None
        return attrs

    def create(self, validated_data):
        user = User.objects.create_user(**validated_data)
        return user


class UserSerializer(serializers.ModelSerializer):
    class Meta:
        model = User
        fields = ['id', 'username', 'display_name', 'email', 'phone', 'role', 'is_anonymous_mode', 'avatar', 'created_at']
        read_only_fields = ['id', 'role', 'created_at']


class UserUpdateSerializer(serializers.ModelSerializer):
    class Meta:
        model = User
        fields = ['display_name', 'email', 'phone', 'is_anonymous_mode', 'avatar']
