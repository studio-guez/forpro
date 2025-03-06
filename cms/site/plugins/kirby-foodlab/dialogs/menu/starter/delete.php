<?php

use Eclypsys\Menu\Starter;

return [
    'pattern' => 'menu/starter/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Starter::delete($id);
    }
];
