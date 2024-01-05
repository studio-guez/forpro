from django.db import models
from tinymce.models import HTMLField
from django.utils.timezone import now
from django.template.defaultfilters import slugify


class Workshop(models.Model):
    title = models.CharField(u'Titre', default='', max_length=125)
    body = HTMLField()
    pub_date = models.DateField(u'Date de publication', default=now)
    slug = models.SlugField(unique=True, default='')
    picture = models.ImageField(u'Illustration 1000x650', default='')

    prepopulated_fields = {"slug": ("title",)}

    def save(self, *args, **kwargs):
        if not self.slug:
            self.slug = slugify(self.title)
        super(Workshop, self).save(*args, **kwargs)
