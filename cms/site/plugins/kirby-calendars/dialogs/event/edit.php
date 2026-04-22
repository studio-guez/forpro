<?php

use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'event/(:any)/edit',
    'load'    => function (string $id) {
        $event = Event::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'info' => [
                        'label' => 'info',
                        'type' => 'info',
                        'content' => 'envoyer un mail de rappel?'
                    ],
                ],
                'value' => $event
            ]
        ];
    },
    'submit' => function (string $id) {
        return Event::update($id, get());
    }
];
