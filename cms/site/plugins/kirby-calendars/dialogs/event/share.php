<?php

use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'event/(:any)/share',
    'load' => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'    => [
                    'email' => [
                        'label' => 'E-mail',
                        'type' => 'email',
                        'required' => true,
                        'help' => 'Email de la personne qui sera assigné à cet événement'
                    ],
                ],
                'submitButton'  => t('share'),
                'size'          => 'large',
            ],
        ];
    },
    'submit' => function (string $id) {
        return Event::share($id, get());
    }
];
