<?php

use MediumSans\KirbyCalendars\Service;

return [
    'pattern' => 'service/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer ce service ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Service::delete($id);
    }
];
