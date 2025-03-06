<?php

use Eclypsys\KirbyCalendars\Event;

return [
    'pattern' => 'event/create',
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
        return Event::create(get());
    }
];
