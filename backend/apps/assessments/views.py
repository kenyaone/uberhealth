from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from .models import Assessment
from .serializers import AssessmentSerializer, AssessmentSubmitSerializer
from .engines import run_assessment
from apps.crisis.detector import trigger_crisis_check


ASSESSMENT_QUESTIONS = {
    'phq9': {
        'title': 'PHQ-9 Depression Assessment',
        'description': 'Over the last 2 weeks, how often have you been bothered by any of the following problems?',
        'scale': ['Not at all (0)', 'Several days (1)', 'More than half the days (2)', 'Nearly every day (3)'],
        'questions': [
            {'key': 'q1', 'text': 'Little interest or pleasure in doing things'},
            {'key': 'q2', 'text': 'Feeling down, depressed, or hopeless'},
            {'key': 'q3', 'text': 'Trouble falling or staying asleep, or sleeping too much'},
            {'key': 'q4', 'text': 'Feeling tired or having little energy'},
            {'key': 'q5', 'text': 'Poor appetite or overeating'},
            {'key': 'q6', 'text': 'Feeling bad about yourself — or that you are a failure or have let yourself or your family down'},
            {'key': 'q7', 'text': 'Trouble concentrating on things, such as reading the newspaper or watching television'},
            {'key': 'q8', 'text': 'Moving or speaking so slowly that other people could have noticed. Or the opposite — being so fidgety or restless that you have been moving around a lot more than usual'},
            {'key': 'q9', 'text': 'Thoughts that you would be better off dead or of hurting yourself in some way'},
        ],
    },
    'gad7': {
        'title': 'GAD-7 Anxiety Assessment',
        'description': 'Over the last 2 weeks, how often have you been bothered by the following problems?',
        'scale': ['Not at all (0)', 'Several days (1)', 'More than half the days (2)', 'Nearly every day (3)'],
        'questions': [
            {'key': 'q1', 'text': 'Feeling nervous, anxious, or on edge'},
            {'key': 'q2', 'text': 'Not being able to stop or control worrying'},
            {'key': 'q3', 'text': 'Worrying too much about different things'},
            {'key': 'q4', 'text': 'Trouble relaxing'},
            {'key': 'q5', 'text': "Being so restless that it's hard to sit still"},
            {'key': 'q6', 'text': 'Becoming easily annoyed or irritable'},
            {'key': 'q7', 'text': 'Feeling afraid, as if something awful might happen'},
        ],
    },
    'audit': {
        'title': 'AUDIT Alcohol Assessment',
        'description': 'The following questions are about your use of alcoholic beverages during the past year.',
        'questions': [
            {'key': 'q1', 'text': 'How often do you have a drink containing alcohol?', 'scale': ['Never (0)', 'Monthly or less (1)', '2-4 times/month (2)', '2-3 times/week (3)', '4+ times/week (4)']},
            {'key': 'q2', 'text': 'How many units of alcohol do you drink on a typical day when you are drinking?', 'scale': ['1-2 (0)', '3-4 (1)', '5-6 (2)', '7-9 (3)', '10+ (4)']},
            {'key': 'q3', 'text': 'How often do you have 6 or more units on one occasion?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q4', 'text': 'How often during the last year have you found that you were not able to stop drinking once you had started?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q5', 'text': 'How often during the last year have you failed to do what was normally expected from you because of drinking?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q6', 'text': 'How often during the last year have you needed a first drink in the morning to get yourself going after a heavy drinking session?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q7', 'text': 'How often during the last year have you had a feeling of guilt or remorse after drinking?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q8', 'text': 'How often during the last year have you been unable to remember what happened the night before because you had been drinking?', 'scale': ['Never (0)', 'Less than monthly (1)', 'Monthly (2)', 'Weekly (3)', 'Daily or almost daily (4)']},
            {'key': 'q9', 'text': 'Have you or someone else been injured as a result of your drinking?', 'scale': ['No (0)', 'Yes, but not in the last year (2)', 'Yes, during the last year (4)']},
            {'key': 'q10', 'text': 'Has a relative, friend, doctor, or other health worker been concerned about your drinking or suggested you cut down?', 'scale': ['No (0)', 'Yes, but not in the last year (2)', 'Yes, during the last year (4)']},
        ],
    },
    'pgsi': {
        'title': 'PGSI Gambling Assessment',
        'description': 'Thinking about the last 12 months, answer the following questions about your gambling behaviour.',
        'scale': ['Never (0)', 'Sometimes (1)', 'Most of the time (2)', 'Almost always (3)'],
        'questions': [
            {'key': 'q1', 'text': 'Have you bet more than you could really afford to lose?'},
            {'key': 'q2', 'text': 'Have you needed to gamble with larger amounts of money to get the same feeling of excitement?'},
            {'key': 'q3', 'text': 'When you gambled, did you go back another day to try to win back the money you lost?'},
            {'key': 'q4', 'text': 'Have you borrowed money or sold anything to get money to gamble?'},
            {'key': 'q5', 'text': 'Have you felt that you might have a problem with gambling?'},
            {'key': 'q6', 'text': 'Has gambling caused you any health problems, including stress or anxiety?'},
            {'key': 'q7', 'text': 'Have people criticised your betting or told you that you had a gambling problem?'},
            {'key': 'q8', 'text': 'Has your gambling caused any financial problems for you or your household?'},
            {'key': 'q9', 'text': 'Have you felt guilty about the way you gamble or what happens when you gamble?'},
        ],
    },
    'ftnd': {
        'title': 'FTND Nicotine Dependence Assessment',
        'description': 'Answer the following questions about your tobacco use.',
        'questions': [
            {'key': 'q1', 'text': 'How soon after you wake up do you smoke your first cigarette?', 'scale': ['After 60 minutes (0)', '31-60 minutes (1)', '6-30 minutes (2)', 'Within 5 minutes (3)']},
            {'key': 'q2', 'text': 'Do you find it difficult to refrain from smoking in places where it is forbidden?', 'scale': ['No (0)', 'Yes (1)']},
            {'key': 'q3', 'text': 'Which cigarette would you hate most to give up?', 'scale': ['Any other (0)', 'The first one in the morning (1)']},
            {'key': 'q4', 'text': 'How many cigarettes per day do you smoke?', 'scale': ['10 or less (0)', '11-20 (1)', '21-30 (2)', '31 or more (3)']},
            {'key': 'q5', 'text': 'Do you smoke more frequently during the first hours after waking than during the rest of the day?', 'scale': ['No (0)', 'Yes (1)']},
            {'key': 'q6', 'text': 'Do you smoke even if you are so ill that you are in bed most of the day?', 'scale': ['No (0)', 'Yes (1)']},
        ],
    },
}


@api_view(['GET'])
def get_questions(request, assessment_type):
    questions = ASSESSMENT_QUESTIONS.get(assessment_type)
    if not questions:
        return Response({'error': 'Unknown assessment type.'}, status=status.HTTP_404_NOT_FOUND)
    return Response(questions)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def submit_assessment(request):
    serializer = AssessmentSubmitSerializer(data=request.data)
    if not serializer.is_valid():
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    assessment_type = serializer.validated_data['assessment_type']
    responses = serializer.validated_data['responses']
    result = run_assessment(assessment_type, responses)

    assessment = Assessment.objects.create(
        user=request.user,
        assessment_type=assessment_type,
        score=result['score'],
        severity=result['severity'],
        interpretation=result['interpretation'],
        recommendations=result['recommendations'],
        responses=responses,
        is_crisis_flag=result.get('is_crisis', False),
    )

    if result.get('is_crisis', False):
        trigger_crisis_check(request.user, 'assessment', f'{assessment_type} Q9 positive', severity='high')

    return Response(AssessmentSerializer(assessment).data, status=status.HTTP_201_CREATED)


class AssessmentHistoryView(generics.ListAPIView):
    serializer_class = AssessmentSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = Assessment.objects.filter(user=self.request.user)
        assessment_type = self.request.query_params.get('type')
        if assessment_type:
            qs = qs.filter(assessment_type=assessment_type)
        return qs
