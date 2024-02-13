<?php

use MediumSans\KirbyCalendars\Calendar;

return [
    'routes' => function () {
        return [
            [
                'pattern' => 'kirby-calendars/calendar/(:any)/update',
                'method' => 'GET',
                'action' => function (string $id) {
                    return Calendar::update($id, get());
                }
            ],
            [
                'pattern' => 'kirby-calendars/get-slots/',
                'method' => 'POST',
                'action' => function () {
                    $data = get();

                    $calendarId = $data['calendarId'];
                    $serviceId = $data['serviceId'];
                    $date = $data['date'];

                    return Calendar::getFreeSlots($calendarId, $serviceId, $date);
                }
            ],
        ];
    }
];
