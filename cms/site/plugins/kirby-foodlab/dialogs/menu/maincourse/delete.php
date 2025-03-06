<?php

use Eclypsys\Menu\Maincourse;

return [
    'pattern' => 'menu/maincourse/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Maincourse::delete($id);
    }
];
