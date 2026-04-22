<?php

use MediumSans\KirbyCalendars\Leave;

return [
    'pattern' => 'leave/(:any)/create',
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
    'submit' => function ($calendarId) {
        $input = get();
        $input['calendar_id'] = $calendarId;
        return Leave::create($input);
    }
];
