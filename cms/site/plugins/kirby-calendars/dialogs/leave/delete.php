<?php

use Eclypsys\KirbyCalendars\Leave;

return [
    'pattern' => 'leave/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer ce congé ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Leave::delete($id);
    }
];
