<?php

use Eclypsys\Menu\Whitewine;

return [
    'pattern' => 'menu/whitewine/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Whitewine::delete($id);
    }
];
