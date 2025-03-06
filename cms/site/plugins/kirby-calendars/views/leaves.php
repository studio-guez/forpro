<?php

use Eclypsys\KirbyCalendars\Calendar;
use Eclypsys\KirbyCalendars\Leave;

return [
    'pattern' => 'kirby-calendars/calendar/(:any)/leaves',
    'action'  => function ($id) {
        $calendar = Calendar::find($id);
        $leaves = Leave::findByCalendarId($id);

        return [
            'component' => 'k-leaves-view',
            'breadcrumb' => [
                [
                    'label' => $calendar['name'],
                    'link'  => '/kirby-calendars/calendars/'
                ],
                [
                    'label' => 'Congés',
                    'link'  => 'events/' . $id
                ]
            ],
            'props' => [
                'calendar' => $calendar,
                'leaves' => $leaves
            ]
        ];
    }
];
