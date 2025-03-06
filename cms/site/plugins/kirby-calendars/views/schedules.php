<?php

use Eclypsys\KirbyCalendars\Calendar;
use Eclypsys\KirbyCalendars\Schedule;

return [
    'pattern' => 'kirby-calendars/calendar/(:any)/schedules',
    'action'  => function ($id) {
        $calendar = Calendar::find($id);
        $schedules = Schedule::findByCalendarId($id);

        return [
            'component'  => 'k-schedules-view',
            'breadcrumb' => [
                [
                    'label' => $calendar['name'],
                    'link'  => '/kirby-calendars/calendars/' . $id
                ],
                [
                    'label' => 'Horaires',
                    'link'  => 'schedules/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
                'schedules' => $schedules,
            ]
        ];
    }
];
