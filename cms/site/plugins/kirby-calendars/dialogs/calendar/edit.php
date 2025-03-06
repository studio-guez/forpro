<?php

use Eclypsys\KirbyCalendars\Calendar;

return [
    'pattern' => 'calendar/(:any)/edit',
    'load'    => function (string $id) {
        $calendar = Calendar::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $calendar
            ]
        ];
    },
    'submit' => function (string $id) {
        return Calendar::update($id, get());
    }
];
