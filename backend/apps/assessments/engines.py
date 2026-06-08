"""
Scoring engines for all 5 validated assessment tools.
Each engine takes a dict of responses and returns score, severity, interpretation, recommendations.
"""

CRISIS_KEYWORDS = [
    'suicide', 'kill myself', 'end my life', 'want to die', 'better off dead',
    'overdose', 'self harm', 'cut myself', 'hurt myself', 'no point living',
    'kujiua', 'kujidhuru',  # Kiswahili
]


def check_crisis_keywords(text):
    text_lower = text.lower()
    return any(keyword in text_lower for keyword in CRISIS_KEYWORDS)


def score_phq9(responses: dict) -> dict:
    """
    PHQ-9: 9 questions, each scored 0-3.
    Total: 0-27.
    Severity: 0-4 minimal, 5-9 mild, 10-14 moderate, 15-19 moderately severe, 20-27 severe.
    Q9 (suicidal ideation) is crisis trigger if score >= 1.
    """
    keys = [f'q{i}' for i in range(1, 10)]
    score = sum(int(responses.get(k, 0)) for k in keys)
    is_crisis = int(responses.get('q9', 0)) >= 1

    if score <= 4:
        severity = 'Minimal'
        interpretation = 'Your responses suggest minimal or no depression. Continue monitoring your mood.'
        recommendations = 'Maintain healthy habits: exercise, sleep, and social connection. Use mood logging to track changes.'
    elif score <= 9:
        severity = 'Mild'
        interpretation = 'You are experiencing mild depression. This is common and treatable.'
        recommendations = 'Consider speaking with a counselor. Explore our depression management lessons and practice daily mood logging.'
    elif score <= 14:
        severity = 'Moderate'
        interpretation = 'You are experiencing moderate depression. Professional support will significantly help.'
        recommendations = 'We strongly recommend booking a session with one of our verified therapists. Regular therapy at this level is very effective.'
    elif score <= 19:
        severity = 'Moderately Severe'
        interpretation = 'You are experiencing moderately severe depression. Please reach out for professional help.'
        recommendations = 'Book a consultation as soon as possible. A therapist can work with you on a treatment plan. If you feel unsafe, please call Befrienders Kenya: 0800 723 253.'
    else:
        severity = 'Severe'
        interpretation = 'You are experiencing severe depression. Immediate professional support is important.'
        recommendations = 'Please book an urgent consultation. If you have thoughts of self-harm, contact Befrienders Kenya: 0800 723 253 or go to your nearest hospital.'

    return {'score': score, 'severity': severity, 'interpretation': interpretation,
            'recommendations': recommendations, 'is_crisis': is_crisis}


def score_gad7(responses: dict) -> dict:
    """
    GAD-7: 7 questions, each scored 0-3. Total: 0-21.
    Severity: 0-4 minimal, 5-9 mild, 10-14 moderate, 15-21 severe.
    """
    keys = [f'q{i}' for i in range(1, 8)]
    score = sum(int(responses.get(k, 0)) for k in keys)

    if score <= 4:
        severity = 'Minimal'
        interpretation = 'Your anxiety levels appear minimal. Keep monitoring.'
        recommendations = 'Practice mindfulness and stress management techniques from our lesson library.'
    elif score <= 9:
        severity = 'Mild'
        interpretation = 'You are experiencing mild anxiety. This is manageable with the right support.'
        recommendations = 'Explore our anxiety management lessons. Try breathing exercises and consider a counseling session.'
    elif score <= 14:
        severity = 'Moderate'
        interpretation = 'You are experiencing moderate anxiety. Professional support can help significantly.'
        recommendations = 'Book a session with a therapist specializing in anxiety. Cognitive-behavioral techniques work well at this level.'
    else:
        severity = 'Severe'
        interpretation = 'You are experiencing severe anxiety. Please seek professional support.'
        recommendations = 'Book an urgent consultation. A therapist can help you manage anxiety effectively. Call Befrienders Kenya: 0800 723 253 if you feel overwhelmed.'

    return {'score': score, 'severity': severity, 'interpretation': interpretation,
            'recommendations': recommendations, 'is_crisis': False}


def score_audit(responses: dict) -> dict:
    """
    AUDIT: 10 questions, scored 0-4 (Q1-Q8) and 0/2/4 (Q9-Q10). Total: 0-40.
    Severity: 0-7 low risk, 8-15 hazardous, 16-19 harmful, 20+ dependent.
    """
    score = 0
    for i in range(1, 9):
        score += int(responses.get(f'q{i}', 0))
    for i in range(9, 11):
        val = int(responses.get(f'q{i}', 0))
        score += val * 2 if val in [1, 2] else val

    if score <= 7:
        severity = 'Low Risk'
        interpretation = 'Your alcohol use is at a low-risk level. Keep it this way.'
        recommendations = 'Learn about the risks of alcohol from our educational lessons.'
    elif score <= 15:
        severity = 'Hazardous'
        interpretation = 'Your alcohol use is at a hazardous level. This puts your health and safety at risk.'
        recommendations = 'Consider cutting back. Talk to a counselor about strategies. Our alcohol recovery lessons can help.'
    elif score <= 19:
        severity = 'Harmful'
        interpretation = 'Your alcohol use is causing harm to your health and wellbeing.'
        recommendations = 'Please book a consultation with an addiction specialist. You do not have to do this alone.'
    else:
        severity = 'Possible Dependence'
        interpretation = 'Your responses suggest possible alcohol dependence. This is a medical condition that requires professional help.'
        recommendations = 'Book an urgent session with an addiction specialist. Consider joining a support group. NACADA Helpline: 1192.'

    return {'score': score, 'severity': severity, 'interpretation': interpretation,
            'recommendations': recommendations, 'is_crisis': False}


def score_pgsi(responses: dict) -> dict:
    """
    PGSI (Problem Gambling Severity Index): 9 questions, scored 0-3.
    Total: 0-27. Severity: 0 non-problem, 1-2 low, 3-7 moderate, 8+ severe.
    """
    keys = [f'q{i}' for i in range(1, 10)]
    score = sum(int(responses.get(k, 0)) for k in keys)

    if score == 0:
        severity = 'Non-Problem'
        interpretation = 'No signs of problematic gambling detected.'
        recommendations = 'Stay aware. If you notice gambling affecting your finances or mood, come back for a re-assessment.'
    elif score <= 2:
        severity = 'Low Risk'
        interpretation = 'You show some signs of gambling risk. Monitor your habits.'
        recommendations = 'Set limits on gambling time and money. Explore our responsible gambling lessons.'
    elif score <= 7:
        severity = 'Moderate Risk'
        interpretation = 'You show moderate signs of problem gambling. Your finances and relationships may be affected.'
        recommendations = 'Book a session with a counselor. Consider a self-exclusion from betting platforms. NACADA Helpline: 1192.'
    else:
        severity = 'Severe Problem Gambling'
        interpretation = 'You show severe signs of gambling disorder. This requires immediate professional attention.'
        recommendations = 'Book an urgent consultation. Consider calling NACADA: 1192. Self-exclusion from betting platforms is strongly recommended.'

    return {'score': score, 'severity': severity, 'interpretation': interpretation,
            'recommendations': recommendations, 'is_crisis': False}


def score_ftnd(responses: dict) -> dict:
    """
    FTND (Fagerström Test for Nicotine Dependence): 6 questions, scored 0-3.
    Total: 0-10. Severity: 0-2 very low, 3-4 low, 5 medium, 6-7 high, 8-10 very high.
    """
    score = 0
    # Q1: 0-2 scoring
    q1_map = {0: 0, 1: 1, 2: 2, 3: 3}
    score += q1_map.get(int(responses.get('q1', 0)), 0)
    # Q2: binary
    score += 1 if int(responses.get('q2', 0)) == 1 else 0
    # Q3: 0-1 scoring
    score += int(responses.get('q3', 0))
    # Q4: binary
    score += 1 if int(responses.get('q4', 0)) == 1 else 0
    # Q5: 0-1 scoring
    score += int(responses.get('q5', 0))
    # Q6: 0-3 scoring
    score += int(responses.get('q6', 0))

    if score <= 2:
        severity = 'Very Low Dependence'
        interpretation = 'You have very low nicotine dependence. Quitting may be easier for you.'
        recommendations = 'Consider setting a quit date. Our tobacco cessation lessons can help.'
    elif score <= 4:
        severity = 'Low Dependence'
        interpretation = 'You have low nicotine dependence.'
        recommendations = 'You can quit with willpower and support. Try nicotine replacement therapy and our cessation lessons.'
    elif score == 5:
        severity = 'Medium Dependence'
        interpretation = 'You have moderate nicotine dependence.'
        recommendations = 'Nicotine replacement therapy is recommended. Book a session with a health counselor.'
    elif score <= 7:
        severity = 'High Dependence'
        interpretation = 'You have high nicotine dependence. Quitting without support is difficult.'
        recommendations = 'Please book a consultation. Combination therapy (NRT + counseling) is most effective at this level.'
    else:
        severity = 'Very High Dependence'
        interpretation = 'You have very high nicotine dependence. Professional support is essential.'
        recommendations = 'Book an urgent consultation with a cessation specialist. Medication plus counseling has the highest quit rates.'

    return {'score': score, 'severity': severity, 'interpretation': interpretation,
            'recommendations': recommendations, 'is_crisis': False}


ENGINES = {
    'phq9': score_phq9,
    'gad7': score_gad7,
    'audit': score_audit,
    'pgsi': score_pgsi,
    'ftnd': score_ftnd,
}


def run_assessment(assessment_type: str, responses: dict) -> dict:
    engine = ENGINES.get(assessment_type)
    if not engine:
        raise ValueError(f'Unknown assessment type: {assessment_type}')
    return engine(responses)
