<?php

use Eclypsys\Menu\Bubblewine;

return [
    'pattern' => 'menu/bubblewine/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Bubblewine::delete($id);
    }
];
