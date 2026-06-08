from django.urls import path
from . import views

urlpatterns = [
    path('questions/<str:assessment_type>/', views.get_questions, name='assessment-questions'),
    path('submit/', views.submit_assessment, name='assessment-submit'),
    path('history/', views.AssessmentHistoryView.as_view(), name='assessment-history'),
]
