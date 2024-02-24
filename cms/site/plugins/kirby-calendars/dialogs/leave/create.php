<?php

use MediumSans\KirbyCalendars\Leave;

return [
    'pattern' => 'leave/create',
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
        return Leave::create(get());
    }
];
