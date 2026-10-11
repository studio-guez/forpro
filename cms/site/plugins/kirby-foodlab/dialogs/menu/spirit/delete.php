<?php

use Eclypsys\Menu\Spirit;

return [
    'pattern' => 'menu/spirit/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Spirit::delete($id);
    }
];
