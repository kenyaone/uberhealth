"""
Run: python seed_therapist.py
Seeds 3 demo verified therapists for testing the booking flow.
"""
import os, django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings.development')
django.setup()

from apps.accounts.models import User
from apps.professionals.models import Professional, Specialization, Language

therapists = [
    {
        'username': 'dr_sarah',
        'display_name': 'Dr. Sarah Kipchoge',
        'email': 'sarah@mhapke.com',
        'password': 'Sarah1234!',
        'kmpdc_license': 'KP-2019-0012',
        'bio': 'I am a compassionate clinical psychologist with 8 years of experience helping individuals overcome depression, trauma, and substance use. I create a safe, non-judgmental space where healing begins.',
        'years_experience': 8,
        'gender': 'female',
        'rate_per_hour': 2500,
        'mpesa_number': '0722000001',
        'specializations': ['Depression & Mood Disorders', 'Trauma & PTSD', 'Substance Use & Addiction'],
        'languages': ['English', 'Kiswahili'],
    },
    {
        'username': 'dr_james',
        'display_name': 'Dr. James Odunga',
        'email': 'james@mhapke.com',
        'password': 'James1234!',
        'kmpdc_license': 'KP-2021-0047',
        'bio': 'Addiction specialist with a focus on alcohol recovery, gambling disorder, and family counseling. I have walked alongside over 200 Kenyan families on their recovery journey. You are not alone.',
        'years_experience': 5,
        'gender': 'male',
        'rate_per_hour': 2000,
        'mpesa_number': '0711000002',
        'specializations': ['Alcohol Recovery', 'Gambling Disorder', 'Relationship & Family'],
        'languages': ['English', 'Kiswahili', 'Luo'],
    },
    {
        'username': 'dr_amina',
        'display_name': 'Dr. Amina Hassan',
        'email': 'amina@mhapke.com',
        'password': 'Amina1234!',
        'kmpdc_license': 'KP-2020-0089',
        'bio': 'Youth and adolescent counselor specializing in anxiety, stress management, and tobacco cessation. Fluent in Kiswahili and Sheng — I meet young people where they are.',
        'years_experience': 6,
        'gender': 'female',
        'rate_per_hour': 1800,
        'mpesa_number': '0733000003',
        'specializations': ['Anxiety & Stress', 'Youth & Adolescents', 'Tobacco Cessation'],
        'languages': ['English', 'Kiswahili', 'Sheng'],
    },
]

for t in therapists:
    # Create user
    user, created = User.objects.get_or_create(
        username=t['username'],
        defaults={
            'display_name': t['display_name'],
            'email': t['email'],
            'role': 'professional',
            'is_anonymous_mode': False,
        }
    )
    if created:
        user.set_password(t['password'])
        user.save()

    # Create professional profile
    prof, _ = Professional.objects.get_or_create(
        user=user,
        defaults={
            'kmpdc_license': t['kmpdc_license'],
            'verification_status': 'verified',
            'bio': t['bio'],
            'years_experience': t['years_experience'],
            'gender': t['gender'],
            'rate_per_hour': t['rate_per_hour'],
            'mpesa_number': t['mpesa_number'],
            'is_available_online': True,
            'is_accepting_new_patients': True,
            'rating': round(__import__('random').uniform(4.2, 4.9), 1),
            'total_sessions': __import__('random').randint(20, 120),
            'total_reviews': __import__('random').randint(10, 80),
        }
    )

    # Attach specializations
    for spec_name in t['specializations']:
        spec = Specialization.objects.filter(name=spec_name).first()
        if spec:
            prof.specializations.add(spec)

    # Attach languages
    for lang_name in t['languages']:
        lang = Language.objects.filter(name=lang_name).first()
        if lang:
            prof.languages.add(lang)

    print(f"  ✓ {t['display_name']} — KES {t['rate_per_hour']}/hr — {t['kmpdc_license']}")

print(f"\nSeeded {len(therapists)} demo therapists. All verified and ready.")
