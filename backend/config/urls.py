from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    path('admin/', admin.site.urls),
    path('api/auth/', include('apps.accounts.urls')),
    path('api/assessments/', include('apps.assessments.urls')),
    path('api/professionals/', include('apps.professionals.urls')),
    path('api/consultations/', include('apps.consultations.urls')),
    path('api/phr/', include('apps.phr.urls')),
    path('api/payments/', include('apps.payments.urls')),
    path('api/crisis/', include('apps.crisis.urls')),
    path('api/subscriptions/', include('apps.subscriptions.urls')),
    path('api/corporate/', include('apps.corporate.urls')),
    path('api/lessons/', include('apps.lessons.urls')),
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
