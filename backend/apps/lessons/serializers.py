from rest_framework import serializers
from .models import Lesson, LessonProgress


class LessonSerializer(serializers.ModelSerializer):
    category_display = serializers.CharField(source='get_category_display', read_only=True)
    user_progress = serializers.SerializerMethodField()

    class Meta:
        model = Lesson
        fields = [
            'id', 'title', 'title_sw', 'slug', 'category', 'category_display',
            'level', 'summary', 'summary_sw', 'duration_minutes',
            'is_premium', 'order', 'thumbnail_emoji', 'key_takeaways', 'key_takeaways_sw',
            'user_progress',
        ]

    def get_user_progress(self, obj):
        request = self.context.get('request')
        if not request or not request.user.is_authenticated:
            return None
        progress = obj.progress.filter(user=request.user).first()
        if not progress:
            return None
        return {'completed': progress.completed, 'progress_pct': progress.progress_pct}


class LessonDetailSerializer(LessonSerializer):
    class Meta(LessonSerializer.Meta):
        fields = LessonSerializer.Meta.fields + ['content', 'content_sw']


class LessonProgressSerializer(serializers.ModelSerializer):
    lesson = LessonSerializer(read_only=True)

    class Meta:
        model = LessonProgress
        fields = ['id', 'lesson', 'completed', 'progress_pct', 'started_at', 'completed_at']
