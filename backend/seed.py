"""
Run: python manage.py shell < seed.py
Seeds specializations, languages, and a test admin user.
"""
import os
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings.development')

import django
django.setup()

from apps.professionals.models import Specialization, Language
from apps.accounts.models import User

specializations = [
    ('Depression & Mood Disorders', 'depression'),
    ('Anxiety & Stress', 'anxiety'),
    ('Substance Use & Addiction', 'substance-use'),
    ('Alcohol Recovery', 'alcohol'),
    ('Gambling Disorder', 'gambling'),
    ('Tobacco Cessation', 'tobacco'),
    ('Trauma & PTSD', 'trauma'),
    ('Relationship & Family', 'family'),
    ('Youth & Adolescents', 'youth'),
    ('LGBTQ+ Affirming', 'lgbtq'),
    ('Grief & Loss', 'grief'),
    ('Workplace Stress', 'workplace'),
]

languages = ['English', 'Kiswahili', 'Sheng', 'Kikuyu', 'Luo', 'Kamba', 'Kalenjin']

for name, slug in specializations:
    Specialization.objects.get_or_create(name=name, defaults={'slug': slug})

for lang in languages:
    Language.objects.get_or_create(name=lang)

if not User.objects.filter(username='admin').exists():
    User.objects.create_superuser(
        username='admin',
        password='admin1234',
        display_name='Platform Admin',
        email='admin@mhapke.com',
        role='admin',
    )

print(f"Seeded {len(specializations)} specializations, {len(languages)} languages, admin user created.")
