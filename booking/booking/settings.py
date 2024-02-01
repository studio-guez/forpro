import os

# Build paths inside the project like this: os.path.join(BASE_DIR, ...)
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# Quick-start development settings - unsuitable for production
# See https://docs.djangoproject.com/en/1.11/howto/deployment/checklist/

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = '***REMOVED***'

# SECURITY WARNING: don't run with debug turned on in production!
DEBUG = False

ALLOWED_HOSTS = ['*']
INTERNAL_IPS = ['127.0.0.1']

ADMINS = [('Samuel', 'samuel@mediumsans.studio'),]
SERVER_EMAIL = 'django@for-pro.ch'

# Application definition

INSTALLED_APPS = [
    'rest_framework',
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    'django.contrib.sitemaps',
    'django_google_maps',
    'django_csv_exports',
    'django.utils',
    'provider',
    'captcha',
    'agenda',
    'timezone_field',
    'gunicorn',
    'djradicale',
    'django_extensions',
]

MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
]

ROOT_URLCONF = 'booking.urls'

TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [os.path.join(BASE_DIR, 'templates')],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.debug',
                'django.template.context_processors.request',
                'django.contrib.auth.context_processors.auth',
                'django.contrib.messages.context_processors.messages',
            ],
        },
    },
]

WSGI_APPLICATION = 'booking.wsgi.application'


# Database
# https://docs.djangoproject.com/en/1.11/ref/settings/#databases

DATABASES = {
    'default': {
        'ENGINE': 'django.db.backends.postgresql_psycopg2',
        'NAME': 'sharpness_dev',
        'USER': 'postgres',
        'PASSWORD': '***REMOVED***',
        'HOST': 'localhost',
        'PORT': '5432',
    }
}


# Password validation
# https://docs.djangoproject.com/en/1.11/ref/settings/#auth-password-validators

AUTH_PASSWORD_VALIDATORS = [
    {
        'NAME': 'django.contrib.auth.password_validation.UserAttributeSimilarityValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.MinimumLengthValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.CommonPasswordValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.NumericPasswordValidator',
    },
]

# EMAIL-PROD
EMAIL_BACKEND = 'django.core.mail.backends.smtp.EmailBackend'
EMAIL_HOST = 'mail.mediumsans.studio'
EMAIL_PORT = '465'
EMAIL_USE_SSL = True
EMAIL_HOST_USER = 'forpro@mediumsans.studio'
EMAIL_HOST_PASSWORD = '***REMOVED***'

# Internationalization
# https://docs.djangoproject.com/en/1.11/topics/i18n/

LANGUAGE_CODE = 'fr-ch'

TIME_ZONE = 'Europe/Zurich'
USE_I18N = True
USE_L10N = True
USE_TZ = True

# CACHE
CACHES = {
    'default': {
        'BACKEND': 'django.core.cache.backends.filebased.FileBasedCache',
        'LOCATION': '/var/tmp/booking_cache',
    }
}

# Static files (CSS, JavaScript, Images)
# https://docs.djangoproject.com/en/1.11/howto/static-files/
GOOGLE_MAPS_API_KEY = '***REMOVED***'

# STATIC FILES
STATIC_URL = '/static/'
MEDIA_URL = '/media/'

STATIC_ROOT = os.path.join(BASE_DIR, 'static')
MEDIA_ROOT = os.path.join(BASE_DIR, 'media')

# CAPTCHA
RECAPTCHA_PUBLIC_KEY = '6LfM1D0UAAAAAPY1jEFF5Q0_P5HKROEMkJYtzxfd'
RECAPTCHA_PRIVATE_KEY = '***REMOVED***'
NOCAPTCHA = True
RECAPTCHA_TESTING = True

# CALENDAR
USE_FULLCALENDAR = True
FIRST_DAY_OF_WEEK = 1
SHORT_DATE_FORMAT = 'd/m/Y'
SHORT_DATETIME_FORMAT = 'l d H:M'

# TINYMCE
TINYMCE_DEFAULT_CONFIG = {
    'theme': "advanced",
}

# SECURITY
SECURE_CONTENT_TYPE_NOSNIFF = True
SECURE_BROWSER_XSS_FILTER = True
SECURE_SSL_REDIRECT = True
SESSION_COOKIE_SECURE = False
CSRF_COOKIE_SECURE = False
X_FRAME_OPTIONS = 'ALLOWALL'
XS_SHARING_ALLOWED_METHODS = ['POST', 'GET', 'OPTIONS', 'PUT', 'DELETE']

# RADICALE
DJRADICALE_CONFIG = {
    'server': {
        'base_prefix': '/pim/',
        'realm': 'Radicale - Password Required',
    },
    'encoding': {
        'request': 'utf-8',
        'stock': 'utf-8',
    },
    'auth': {
        'type': 'custom',
        'custom_handler': 'djradicale.auth.django',
    },
    'rights': {
        'type': 'custom',
        'custom_handler': 'djradicale.rights.django',
    },
    'storage': {
        'type': 'custom',
        'custom_handler': 'djradicale.storage.django',
    },
    'well-known': {
        'carddav': '/pim/%(user)s/addressbook.vcf',
        'caldav': '/pim/%(user)s/calendar.ics',
    },
}

DJRADICALE_RIGHTS = {
    'rw': {
        'user': '.+',
        'collection': '^%(login)s/[a-z0-9\.\-_]+\.(vcf|ics)$',
        'permission': 'rw',
    },
    'rw-root': {
        'user': '.+',
        'collection': '^%(login)s$',
        'permission': 'rw',
    },
}
