<?php

use Eclypsys\Menu\Redwine;

return [
    'pattern' => 'menu/redwine/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Redwine::delete($id);
    }
];
