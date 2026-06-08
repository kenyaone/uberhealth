from django.urls import path
from . import views

urlpatterns = [
    path('mood/', views.MoodLogListCreateView.as_view(), name='mood-list'),
    path('mood/<int:pk>/', views.MoodLogDetailView.as_view(), name='mood-detail'),
    path('cravings/', views.CravingLogListCreateView.as_view(), name='craving-list'),
    path('sobriety/', views.SobrietyTrackerListCreateView.as_view(), name='sobriety-list'),
    path('sobriety/<int:pk>/', views.SobrietyTrackerDetailView.as_view(), name='sobriety-detail'),
    path('sobriety/<int:pk>/update-streak/', views.update_sobriety_streak, name='update-streak'),
    path('summary/', views.phr_summary, name='phr-summary'),
]
