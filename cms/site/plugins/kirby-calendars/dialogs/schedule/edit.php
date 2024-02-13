<?php

use MediumSans\KirbyCalendars\Schedule;

return [
    'pattern' => 'schedule/(:any)/edit',
    'load'    => function (string $id) {
        $schedule = Schedule::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $schedule
            ]
        ];
    },
    'submit' => function (string $id) {
        return Schedule::update($id, get());
    }
];
