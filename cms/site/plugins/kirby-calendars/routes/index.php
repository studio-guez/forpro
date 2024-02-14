<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use MediumSans\KirbyCalendars\Calendar;

return [
    [
        'pattern' => 'kirby-calendars/calendar/(:any)/update',
        'method' => 'GET',
        'action' => function (string $id) {
            return Calendar::update($id, get());
        }
    ],
    [
        'pattern' => 'kirby-calendars/get-slots/(:any)/(:any)/(:any)',
        'method' => 'GET',
        'action' => function ($calendarId, $serviceId, $date) {
            return response::json(
                Json::encode(
                    Calendar::getFreeSlots($calendarId, $serviceId, $date)
                )
            );
        }
    ],
    [
        'pattern' => 'kirby-calendars/add-slot',
        'method' => 'POST',
        'action' => function () {
            $data = get();

            $calendarId = $data['calendarId'];
            $serviceId = $data['serviceId'];
            $date = $data['date'];
            $infos = $data['infos'];

            return response::json(
                Json::encode(
                    Calendar::addEvent(
                        $calendarId,
                        $serviceId,
                        $date,
                        $infos
                    )
                )
            );
        }
    ],
];
