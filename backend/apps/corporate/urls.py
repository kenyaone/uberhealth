from django.urls import path
from . import views

urlpatterns = [
    path('tiers/', views.eap_tiers, name='eap-tiers'),
    path('apply/', views.apply_eap, name='eap-apply'),
    path('mpesa/callback/', views.eap_mpesa_callback, name='eap-mpesa-callback'),
    path('mine/', views.my_eap, name='my-eap'),
    path('admin/list/', views.admin_eap_list, name='admin-eap-list'),
    path('admin/<int:pk>/activate/', views.admin_activate_eap, name='admin-activate-eap'),
]
