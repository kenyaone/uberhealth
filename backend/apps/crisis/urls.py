from django.urls import path
from . import views

urlpatterns = [
    path('hotlines/', views.hotlines, name='hotlines'),
    path('check/', views.check_text, name='crisis-check'),
]
