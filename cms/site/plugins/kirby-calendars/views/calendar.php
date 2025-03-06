<?php

use Eclypsys\KirbyCalendars\Calendar;
use Eclypsys\KirbyCalendars\Schedule;
use Eclypsys\KirbyCalendars\Service;

return [
    'pattern' => 'kirby-calendars/calendars/(:any)',
    'action'  => function (string $id) {
        $calendar = Calendar::find($id);

        $calendar['nbrServices'] = count(Service::findByCalendarId($id));
        $calendar['scheduleState'] = Schedule::getScheduleStatusForCalendar($id);

        return [
            'component' => 'k-calendar-view',
            'breadcrumb' => [
                [
                    'label' => $calendar['name'],
                    'link'  => '/kirby-calendars/calendars/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
            ]
        ];
    }
];
