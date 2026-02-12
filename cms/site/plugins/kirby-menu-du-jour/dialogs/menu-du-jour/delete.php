<?php

use Eclypsys\MenuDuJour\MenuDuJour;

return [
    'pattern' => 'menu-du-jour/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer ce menu du jour ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return MenuDuJour::delete($id);
    }
];
