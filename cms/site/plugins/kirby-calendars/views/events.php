<?php

use MediumSans\KirbyCalendars\Calendar;
use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'kirby-calendars/calendar/(:any)/events',
    'action'  => function ($id) {
        $calendar = Calendar::find($id);
        $events = Event::findByCalendarId($id);

        return [
            'component' => 'k-events-view',
            'breadcrumb' => [
                [
                    'label' => $calendar['name'],
                    'link'  => '/kirby-calendars/calendars/' . $id
                ],
                [
                    'label' => 'Évènements',
                    'link'  => 'events/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
                'events' => $events
            ]
        ];
    }
];
