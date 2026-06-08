from django.contrib.auth.models import AbstractUser
from django.db import models


class User(AbstractUser):
    ROLE_USER = 'user'
    ROLE_PROFESSIONAL = 'professional'
    ROLE_ADMIN = 'admin'
    ROLE_CHOICES = [(ROLE_USER, 'User'), (ROLE_PROFESSIONAL, 'Professional'), (ROLE_ADMIN, 'Admin')]

    display_name = models.CharField(max_length=100, help_text='Name shown to professionals. Can be pseudonym.')
    email = models.EmailField(blank=True, null=True)
    phone = models.CharField(max_length=15, blank=True, null=True)
    role = models.CharField(max_length=20, choices=ROLE_CHOICES, default=ROLE_USER)
    is_anonymous_mode = models.BooleanField(default=True)
    avatar = models.ImageField(upload_to='avatars/', blank=True, null=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    USERNAME_FIELD = 'username'
    REQUIRED_FIELDS = ['display_name']

    class Meta:
        db_table = 'users'

    def __str__(self):
        return self.username

    @property
    def is_professional(self):
        return self.role == self.ROLE_PROFESSIONAL
