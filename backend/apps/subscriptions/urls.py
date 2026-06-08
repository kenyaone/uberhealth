from django.urls import path
from . import views

urlpatterns = [
    path('plans/', views.plans, name='plans'),
    path('mine/', views.my_subscription, name='my-subscription'),
    path('subscribe/', views.subscribe, name='subscribe'),
    path('mpesa/callback/', views.subscription_mpesa_callback, name='subscription-mpesa-callback'),
    path('access/', views.check_feature_access, name='check-feature-access'),
]
