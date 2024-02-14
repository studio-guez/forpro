<?php

use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'event/(:any)/edit',
    'load'    => function (string $id) {
        $event = Event::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $event
            ]
        ];
    },
    'submit' => function (string $id) {
        return Event::update($id, get());
    }
];
