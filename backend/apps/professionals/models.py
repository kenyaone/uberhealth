from django.db import models
from apps.accounts.models import User


class Specialization(models.Model):
    name = models.CharField(max_length=100, unique=True)
    slug = models.SlugField(unique=True)

    def __str__(self):
        return self.name


class Language(models.Model):
    name = models.CharField(max_length=50, unique=True)

    def __str__(self):
        return self.name


class Professional(models.Model):
    GENDER_MALE = 'male'
    GENDER_FEMALE = 'female'
    GENDER_OTHER = 'other'
    GENDER_CHOICES = [(GENDER_MALE, 'Male'), (GENDER_FEMALE, 'Female'), (GENDER_OTHER, 'Other')]

    VERIFICATION_PENDING = 'pending'
    VERIFICATION_VERIFIED = 'verified'
    VERIFICATION_REJECTED = 'rejected'
    VERIFICATION_CHOICES = [
        (VERIFICATION_PENDING, 'Pending'),
        (VERIFICATION_VERIFIED, 'Verified'),
        (VERIFICATION_REJECTED, 'Rejected'),
    ]

    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='professional_profile')
    kmpdc_license = models.CharField(max_length=50, unique=True)
    verification_status = models.CharField(max_length=20, choices=VERIFICATION_CHOICES, default=VERIFICATION_PENDING)
    specializations = models.ManyToManyField(Specialization, blank=True)
    languages = models.ManyToManyField(Language, blank=True)
    rate_per_hour = models.DecimalField(max_digits=10, decimal_places=2, default=2000)
    bio = models.TextField()
    years_experience = models.IntegerField(default=0)
    gender = models.CharField(max_length=10, choices=GENDER_CHOICES, default=GENDER_OTHER)
    rating = models.DecimalField(max_digits=3, decimal_places=2, default=0.0)
    total_sessions = models.IntegerField(default=0)
    total_reviews = models.IntegerField(default=0)
    is_available_online = models.BooleanField(default=True)
    is_accepting_new_patients = models.BooleanField(default=True)
    profile_photo = models.ImageField(upload_to='professionals/', blank=True, null=True)
    mpesa_number = models.CharField(max_length=15, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = 'professionals'

    def __str__(self):
        return f"Dr. {self.user.display_name}"


class ProfessionalAvailability(models.Model):
    DAY_CHOICES = [
        (0, 'Monday'), (1, 'Tuesday'), (2, 'Wednesday'),
        (3, 'Thursday'), (4, 'Friday'), (5, 'Saturday'), (6, 'Sunday'),
    ]
    professional = models.ForeignKey(Professional, on_delete=models.CASCADE, related_name='availability')
    day_of_week = models.IntegerField(choices=DAY_CHOICES)
    start_time = models.TimeField()
    end_time = models.TimeField()
    is_active = models.BooleanField(default=True)

    class Meta:
        db_table = 'professional_availability'
        unique_together = ['professional', 'day_of_week', 'start_time']
