from django.contrib import admin
from workshop.models import Workshop
# Register your models here.

Models = [Workshop]
admin.site.register(Models)
