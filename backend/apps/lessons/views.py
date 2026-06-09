from rest_framework import generics, status
from rest_framework.decorators import api_view, permission_classes
from rest_framework.permissions import IsAuthenticated, AllowAny
from rest_framework.response import Response
from django.utils import timezone
from .models import Lesson, LessonProgress
from .serializers import LessonSerializer, LessonDetailSerializer, LessonProgressSerializer


class LessonListView(generics.ListAPIView):
    serializer_class = LessonSerializer
    permission_classes = [AllowAny]

    def get_queryset(self):
        qs = Lesson.objects.filter(is_published=True)
        category = self.request.query_params.get('category')
        if category:
            qs = qs.filter(category=category)
        lang = self.request.query_params.get('lang')
        if lang:
            qs = qs.filter(language=lang)
        return qs

    def get_serializer_context(self):
        return {'request': self.request}


class LessonDetailView(generics.RetrieveAPIView):
    serializer_class = LessonDetailSerializer
    permission_classes = [AllowAny]
    lookup_field = 'slug'

    def get_queryset(self):
        return Lesson.objects.filter(is_published=True)

    def get_serializer_context(self):
        return {'request': self.request}

    def retrieve(self, request, *args, **kwargs):
        lesson = self.get_object()
        # Check premium gate
        if lesson.is_premium and request.user.is_authenticated:
            from apps.subscriptions.models import Subscription
            has_premium = Subscription.objects.filter(
                user=request.user,
                status=Subscription.STATUS_ACTIVE,
                expires_at__gt=timezone.now(),
                plan__tier__in=['premium', 'pro'],
            ).exists()
            if not has_premium:
                # Return lesson metadata but not full content
                serializer = LessonSerializer(lesson, context={'request': request})
                data = serializer.data
                data['locked'] = True
                data['content'] = None
                return Response(data)
        serializer = self.get_serializer(lesson)
        data = serializer.data
        data['locked'] = False
        return Response(data)


@api_view(['POST'])
@permission_classes([IsAuthenticated])
def update_progress(request, slug):
    try:
        lesson = Lesson.objects.get(slug=slug, is_published=True)
    except Lesson.DoesNotExist:
        return Response({'error': 'Not found.'}, status=status.HTTP_404_NOT_FOUND)

    progress_pct = request.data.get('progress_pct', 0)
    completed = request.data.get('completed', False)

    progress, _ = LessonProgress.objects.get_or_create(
        user=request.user, lesson=lesson
    )
    progress.progress_pct = min(100, max(0, int(progress_pct)))
    if completed or progress.progress_pct == 100:
        progress.completed = True
        progress.completed_at = timezone.now()
    progress.save()

    return Response(LessonProgressSerializer(progress).data)


@api_view(['GET'])
@permission_classes([IsAuthenticated])
def my_progress(request):
    progress = LessonProgress.objects.filter(
        user=request.user
    ).select_related('lesson').order_by('-started_at')
    return Response(LessonProgressSerializer(progress, many=True).data)
