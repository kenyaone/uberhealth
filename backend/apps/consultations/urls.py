from django.urls import path
from . import views

urlpatterns = [
    path('book/', views.book_consultation, name='book-consultation'),
    path('mine/', views.my_consultations, name='my-consultations'),
    path('<str:consultation_id>/', views.consultation_detail, name='consultation-detail'),
    path('<str:consultation_id>/join/', views.join_consultation, name='join-consultation'),
    path('<str:consultation_id>/rate/', views.rate_consultation, name='rate-consultation'),
    path('<str:consultation_id>/notes/', views.add_professional_notes, name='professional-notes'),
    path('professional/list/', views.professional_consultations, name='professional-consultations'),
]
