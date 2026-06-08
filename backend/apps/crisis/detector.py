from .models import CrisisEvent

CRISIS_KEYWORDS = [
    'suicide', 'kill myself', 'end my life', 'want to die', 'better off dead',
    'overdose', 'self harm', 'cut myself', 'hurt myself', 'no point living',
    'kujiua', 'kujidhuru', 'jiua', 'maisha haina maana',
]

HOTLINES = [
    {'name': 'Befrienders Kenya', 'number': '0800 723 253', 'available': '24/7'},
    {'name': 'NACADA Helpline', 'number': '1192', 'available': '24/7'},
    {'name': 'Emergency Services', 'number': '999', 'available': '24/7'},
]


def detect_keywords(text: str) -> list:
    text_lower = text.lower()
    return [kw for kw in CRISIS_KEYWORDS if kw in text_lower]


def trigger_crisis_check(user, source: str, content: str, severity: str = 'medium'):
    detected = detect_keywords(content)
    if not detected and severity not in ['high', 'critical']:
        return None

    event = CrisisEvent.objects.create(
        user=user,
        trigger_source=source,
        content=content,
        severity=severity,
        keywords_detected=detected,
        response_action='hotline_shown',
    )
    return event


def get_hotlines():
    return HOTLINES
