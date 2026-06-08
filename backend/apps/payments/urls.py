from django.urls import path
from . import views

urlpatterns = [
    path('mpesa/callback/', views.mpesa_callback, name='mpesa-callback'),
    path('status/<str:consultation_id>/', views.payment_status, name='payment-status'),
]
