<?php

use Eclypsys\KirbyCalendars\Schedule;

return [
    'pattern' => 'schedule/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet horaire ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Schedule::delete($id);
    }
];
