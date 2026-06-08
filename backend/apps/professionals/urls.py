from django.urls import path
from . import views

urlpatterns = [
    path('', views.ProfessionalListView.as_view(), name='professional-list'),
    path('<int:pk>/', views.ProfessionalDetailView.as_view(), name='professional-detail'),
    path('register/', views.register_professional, name='professional-register'),
    path('me/', views.my_professional_profile, name='my-professional-profile'),
    path('specializations/', views.SpecializationListView.as_view(), name='specialization-list'),
    path('languages/', views.LanguageListView.as_view(), name='language-list'),
    # Admin
    path('admin/pending/', views.pending_applications, name='pending-applications'),
    path('admin/all/', views.all_professionals_admin, name='all-professionals-admin'),
    path('admin/<int:pk>/verify/', views.verify_professional, name='verify-professional'),
]
