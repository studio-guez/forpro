<?php

use MediumSans\KirbyCalendars\Calendar;
use MediumSans\KirbyCalendars\Schedule;
use MediumSans\KirbyCalendars\Service;

return [
    'pattern' => 'kirby-calendars/calendars',
    'action'  => function () {
        $calendars = Calendar::list();

        foreach($calendars as $id => $calendar) {
            $calendars[$id]['nbrServices'] = count(Service::findByCalendarId($id));
            $calendars[$id]['scheduleState'] = Schedule::getScheduleStatusForCalendar($id);
        }

        return [
            'component' => 'k-calendars-view',
            'props' => [
                'calendars' => $calendars,
            ]
        ];
    }
];
