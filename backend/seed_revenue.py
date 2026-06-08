"""Run: python seed_revenue.py — seeds subscription plans and EAP tiers."""
import os, django
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings.development')
django.setup()

from apps.subscriptions.models import Plan
from apps.corporate.models import EAPTier

# ── Subscription Plans ────────────────────────────────────────────────────────
plans = [
    {
        'name': 'Free',
        'tier': 'free',
        'price_kes': 0,
        'interval': 'monthly',
        'assessment_limit': 3,
        'lesson_limit': 8,
        'features': [
            '3 assessments per month',
            '8 lessons per month',
            'Mood tracking',
            'Sobriety tracker',
            'Crisis support',
        ],
    },
    {
        'name': 'Premium',
        'tier': 'premium',
        'price_kes': 299,
        'interval': 'monthly',
        'assessment_limit': -1,
        'lesson_limit': -1,
        'features': [
            'Unlimited assessments',
            'All lessons (50+)',
            'Unlimited mood & craving logs',
            'Priority booking (skip queue)',
            'Session history export (PDF)',
            'Monthly progress report',
            'No ads, ever',
        ],
    },
    {
        'name': 'Premium Annual',
        'tier': 'premium',
        'price_kes': 2990,
        'interval': 'annual',
        'assessment_limit': -1,
        'lesson_limit': -1,
        'features': [
            'Everything in Premium',
            '2 months free (vs monthly)',
            'Priority customer support',
            'Early access to new features',
        ],
    },
    {
        'name': 'Professional Pro',
        'tier': 'pro',
        'price_kes': 500,
        'interval': 'monthly',
        'assessment_limit': -1,
        'lesson_limit': -1,
        'features': [
            'Profile analytics (who viewed you)',
            'Priority placement in search',
            'Unlimited session notes templates',
            'KMPDC-compliant documentation',
            'Monthly earnings report',
            'Dedicated support line',
        ],
    },
]

for p in plans:
    Plan.objects.get_or_create(
        name=p['name'],
        defaults={k: v for k, v in p.items() if k != 'name'}
    )
    print(f"  ✓ Plan: {p['name']} — KES {p['price_kes']}/{p['interval']}")

# ── EAP Tiers ─────────────────────────────────────────────────────────────────
eap_tiers = [
    {
        'name': 'Starter',
        'min_employees': 5,
        'max_employees': 25,
        'price_kes_annual': 50000,
        'sessions_per_employee': 4,
        'features': [
            'Up to 25 employees',
            '4 sessions per employee per year',
            'Anonymous employee access',
            'HR usage dashboard',
            'All 5 assessment tools',
            'Dedicated onboarding call',
        ],
    },
    {
        'name': 'Growth',
        'min_employees': 26,
        'max_employees': 100,
        'price_kes_annual': 180000,
        'sessions_per_employee': 6,
        'features': [
            'Up to 100 employees',
            '6 sessions per employee per year',
            'Anonymous employee access',
            'HR analytics dashboard',
            'Monthly utilisation reports',
            'Group wellness workshops (2/year)',
            'Priority booking for employees',
        ],
    },
    {
        'name': 'Enterprise',
        'min_employees': 101,
        'max_employees': 500,
        'price_kes_annual': 500000,
        'sessions_per_employee': 8,
        'features': [
            'Up to 500 employees',
            '8 sessions per employee per year',
            'Anonymous employee access',
            'Full HR analytics suite',
            'Quarterly wellness reports',
            'Group workshops (4/year)',
            'Dedicated account manager',
            'Custom onboarding & comms',
            'SSO integration',
        ],
    },
]

for t in eap_tiers:
    EAPTier.objects.get_or_create(
        name=t['name'],
        defaults={k: v for k, v in t.items() if k != 'name'}
    )
    print(f"  ✓ EAP: {t['name']} — KES {t['price_kes_annual']:,}/year ({t['min_employees']}–{t['max_employees']} staff)")

print(f"\nSeeded {len(plans)} subscription plans and {len(eap_tiers)} EAP tiers.")
