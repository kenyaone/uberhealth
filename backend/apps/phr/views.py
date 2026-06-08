from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated
from rest_framework.response import Response
from django.utils import timezone
from datetime import date, timedelta
from .models import MoodLog, CravingLog, SobrietyTracker
from .serializers import MoodLogSerializer, CravingLogSerializer, SobrietyTrackerSerializer
from apps.crisis.detector import trigger_crisis_check


class MoodLogListCreateView(generics.ListCreateAPIView):
    serializer_class = MoodLogSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = MoodLog.objects.filter(user=self.request.user)
        days = self.request.query_params.get('days')
        if days:
            since = timezone.now() - timedelta(days=int(days))
            qs = qs.filter(logged_at__gte=since)
        return qs

    def perform_create(self, serializer):
        log = serializer.save(user=self.request.user)
        if log.notes:
            trigger_crisis_check(self.request.user, 'mood_log', log.notes)


class MoodLogDetailView(generics.RetrieveUpdateDestroyAPIView):
    serializer_class = MoodLogSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return MoodLog.objects.filter(user=self.request.user)


class CravingLogListCreateView(generics.ListCreateAPIView):
    serializer_class = CravingLogSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        qs = CravingLog.objects.filter(user=self.request.user)
        substance = self.request.query_params.get('substance')
        if substance:
            qs = qs.filter(substance=substance)
        return qs

    def perform_create(self, serializer):
        log = serializer.save(user=self.request.user)
        if not log.resisted:
            tracker = SobrietyTracker.objects.filter(user=self.request.user, substance=log.substance).first()
            if tracker:
                tracker.total_relapses += 1
                tracker.current_streak = 0
                tracker.start_date = log.logged_at.date() if log.logged_at else date.today()
                tracker.save()


class SobrietyTrackerListCreateView(generics.ListCreateAPIView):
    serializer_class = SobrietyTrackerSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return SobrietyTracker.objects.filter(user=self.request.user)

    def perform_create(self, serializer):
        serializer.save(user=self.request.user)


class SobrietyTrackerDetailView(generics.RetrieveUpdateDestroyAPIView):
    serializer_class = SobrietyTrackerSerializer
    permission_classes = [IsAuthenticated]

    def get_queryset(self):
        return SobrietyTracker.objects.filter(user=self.request.user)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def update_sobriety_streak(request, pk):
    try:
        tracker = SobrietyTracker.objects.get(pk=pk, user=request.user)
    except SobrietyTracker.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)

    today = date.today()
    delta = (today - tracker.start_date).days
    tracker.current_streak = delta
    if delta > tracker.longest_streak:
        tracker.longest_streak = delta
    tracker.save()
    return Response(SobrietyTrackerSerializer(tracker).data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def phr_summary(request):
    user = request.user
    latest_mood = MoodLog.objects.filter(user=user).first()
    sobriety = SobrietyTracker.objects.filter(user=user, is_active=True)
    craving_count_week = CravingLog.objects.filter(
        user=user, logged_at__gte=timezone.now() - timedelta(days=7)
    ).count()
    return Response({
        'latest_mood': MoodLogSerializer(latest_mood).data if latest_mood else None,
        'sobriety_trackers': SobrietyTrackerSerializer(sobriety, many=True).data,
        'cravings_this_week': craving_count_week,
    })
