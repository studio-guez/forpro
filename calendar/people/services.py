from django import forms

from googleapiclient.discovery import build
from oauth2client.service_account import ServiceAccountCredentials
from service_objects.services import Service

import httplib2

service_account_email = 'forpro-django@forpro-calendrier.iam.gserviceaccount.com'
CLIENT_SECRET_FILE = 'sharpness-calendrier-21eb29905a6d.json'
SCOPES = 'https://www.googleapis.com/auth/calendar'
scopes = [SCOPES]


def build_service():
    credentials = ServiceAccountCredentials.from_json_keyfile_name(
        filename=CLIENT_SECRET_FILE,
        scopes=SCOPES
    )

    http = credentials.authorize(httplib2.Http())

    service = build('calendar', 'v3', http=http)

    return service


class FindNextFreeTime(Service):
    calendar_id_lst = []
    start_datetime = ''
    end_datetime = ''

    def process(self):

        calendar_id_lst = self.cleaned_data['calendar_id_lst']
        start_datetime = self.cleaned_data['start_datetime']
        end_datetime = self.cleaned_data['end_datetime']
        items_content = ''

        service = build_service()

        for c in calendar_id_lst:
            items_content += "'id': " + c + ','

        event = service.events().insert(calendarId=1, body={
            'timeMin': start_datetime.isoformat(),
            'timeMax': end_datetime.isoformat(),
            'items': {
                items_content
            },
        }).execute()

        print(event)
