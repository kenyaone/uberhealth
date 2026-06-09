from django.urls import path
from . import views
from . import pro_dashboard_views as pdv

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
    # Professional dashboard
    path('dashboard/summary/', pdv.pro_dashboard_summary, name='pro-dashboard-summary'),
    path('dashboard/sessions/', pdv.pro_sessions, name='pro-sessions'),
    path('dashboard/earnings/', pdv.pro_earnings, name='pro-earnings'),
    path('dashboard/patients/', pdv.pro_patients, name='pro-patients'),
    path('dashboard/availability/', pdv.pro_availability, name='pro-availability'),
    path('dashboard/profile/', pdv.pro_update_profile, name='pro-update-profile'),
]
