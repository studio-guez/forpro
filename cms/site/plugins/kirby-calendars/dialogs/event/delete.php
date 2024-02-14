<?php

use MediumSans\KirbyCalendars\Event;

return [
    'pattern' => 'event/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer ce calendrier ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Event::delete($id);
    }
];
