<?php

use MediumSans\KirbyCalendars\Calendar;
use MediumSans\KirbyCalendars\Service;

return [
    'pattern' => 'kirby-calendars/calendar/(:any)/services',
    'action'  => function ($id) {
        $calendar = Calendar::find($id);
        $services = Service::findByCalendarId($id);

        return [
            'component'  => 'k-services-view',
            'breadcrumb' => [
                [
                    'label' => $calendar['name'],
                    'link'  => '/kirby-calendars/calendars/' . $id
                ],
                [
                    'label' => 'Services',
                    'link'  => 'services/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
                'services' => $services,
            ]
        ];
    }
];
