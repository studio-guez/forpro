from django.core.mail import EmailMultiAlternatives
from django.template.loader import get_template
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from datetime import datetime, timedelta

from agenda.models import Event
from provider.models import ProviderService, Provider


class AppointmentAPI(APIView):

    def post(self, request, format=None):
        # Extract data from request
        data = request.data
        service = ProviderService.objects.get(id=data['serviceId'])
        provider = Provider.objects.get(id=data['providerId'])

        firstname = data['firstName']
        lastname = data['lastName']
        phone = data['phone']
        email = data['email']
        notes = data['notes'] if data['notes'] is not None else ''

        event_date = datetime.strptime(data['start'], "%Y-%m-%d %H:%M:%S")

        start_time = event_date
        duration = timedelta(hours=service.duration.hour, minutes=service.duration.minute)
        end_time = start_time + duration

        description = firstname + ' ' + lastname + '\n' + email + '\n' + phone + '\n' + notes

        # Create Event
        event = Event.create(
            title=service.name,
            event_date=event_date,
            start_time=start_time.time(),
            end_time=end_time.time(),
            author=email,
            description=description,
            collection=provider.person_set.first().calendar,
            timezone=provider.timezone
        )
        event.save()

        ctx = {
            'event': event,
            'service': service,
        }

        html = get_template('appointment_confirm.html')
        html_content = html.render(ctx)

        msg = EmailMultiAlternatives(
            "Confirmez votre rendez-vous !",
            html_content,
            "forpro@mediumsans.studio",
            [email]
        )
        msg.attach_alternative(html_content, "text/html")

        msg.send()

        return Response({'status': 'appointment set'}, status=status.HTTP_201_CREATED)
