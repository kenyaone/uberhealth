from django.urls import path
from . import views

urlpatterns = [
    path('', views.LessonListView.as_view(), name='lesson-list'),
    path('my-progress/', views.my_progress, name='my-progress'),
    path('<slug:slug>/', views.LessonDetailView.as_view(), name='lesson-detail'),
    path('<slug:slug>/progress/', views.update_progress, name='update-progress'),
]
