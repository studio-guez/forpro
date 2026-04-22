<?php

use MediumSans\KirbyCalendars\Calendar;

return [
    'pattern' => 'calendar/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer ce calendrier ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Calendar::delete($id);
    }
];
