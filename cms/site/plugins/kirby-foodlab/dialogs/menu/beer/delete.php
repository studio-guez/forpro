<?php

use Eclypsys\Menu\Beer;

return [
    'pattern' => 'menu/beer/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Beer::delete($id);
    }
];
