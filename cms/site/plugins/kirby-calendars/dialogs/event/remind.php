<?php

use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'event/(:any)/remind',
    'load'    => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'envoyer un mail de rappel?',
                'submitButton'  => t('share'),
            ]
        ];
    },
    'submit' => function (string $id) {
        return Event::sendReminding($id, get());
    }
];
