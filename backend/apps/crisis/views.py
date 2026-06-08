from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import AllowAny, IsAuthenticated
from rest_framework.response import Response
from .detector import get_hotlines, trigger_crisis_check, detect_keywords


@api_view(['GET'])
@permission_classes([AllowAny])
def hotlines(request):
    return Response({'hotlines': get_hotlines()})


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def check_text(request):
    text = request.data.get('text', '')
    detected = detect_keywords(text)
    is_crisis = len(detected) > 0
    response_data = {'is_crisis': is_crisis, 'hotlines': []}
    if is_crisis:
        trigger_crisis_check(request.user, 'mood_log', text, severity='high')
        response_data['hotlines'] = get_hotlines()
    return Response(response_data)
