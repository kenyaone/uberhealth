from django.contrib import admin
from .models import MoodLog, CravingLog, SobrietyTracker

admin.site.register(MoodLog)
admin.site.register(CravingLog)
admin.site.register(SobrietyTracker)
