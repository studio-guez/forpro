<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use MediumSans\KirbyCalendars\Calendar;
use MediumSans\KirbyCalendars\Schedule;
use MediumSans\KirbyCalendars\Service;
use MediumSans\KirbyCalendars\Event;

return [
    [
        'pattern' => 'kirby-calendars/calendar/(:any)/update',
        'method' => 'GET',
        'action' => function (string $id) {
            return Calendar::update($id, get());
        }
    ],
    [
        'pattern' => 'kirby-calendars/(:any)/services',
        'method' => 'GET',
        'action' => function ($calendarId) {
            return response::json(
                Json::encode(
                    Service::findByCalendarId($calendarId)
                )
            );
        }
    ],
    [
        'pattern' => 'kirby-calendars/(:any)/schedules',
        'method' => 'GET',
        'action' => function ($calendarId) {
            return response::json(
                Json::encode(
                    Schedule::findByCalendarId($calendarId)
                )
            );
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
            $data = json_decode(file_get_contents('php://input'));

            $calendarId = $data->calendarId;
            $serviceId = $data->serviceId;
            $subjectId = $data->subject;

            $date = $data->slot;
            $inf = $data->infos;

            $infos = [
                'firstname' => $inf->firstname,
                'lastname' => $inf->lastname,
                'phone' => $inf->phone,
                'email' => $inf->email,
            ];

            return response::json(
                Json::encode(
                    Event::add(
                        $calendarId,
                        $subjectId,
                        $serviceId,
                        $date,
                        $infos
                    )
                )
            );
        }
    ],
    [
        'pattern' => 'kirby-calendars/(:any)/confirm',
        'method' => 'GET',
        'action' => function ($eventId) {
            return  response::json(Json::encode(Event::validateEvent($eventId)));
        }
    ]
];
