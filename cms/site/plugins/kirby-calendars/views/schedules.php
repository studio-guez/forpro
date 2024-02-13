<?php

use MediumSans\KirbyCalendars\Calendar;
use MediumSans\KirbyCalendars\Schedule;

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
                    'link'  => 'calendars/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
                'schedules' => $schedules,
            ]
        ];
    }
];
