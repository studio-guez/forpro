<?php

use Eclypsys\KirbyCalendars\Calendar;

return [
    'pattern' => 'calendar/create',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'        => require __DIR__ . '/fields.php',
                'submitButton'  => t('create'),
                'size'          => 'large',
            ],
        ];
    },
    'submit' => function () {
        return Calendar::create(get());
    }
];
