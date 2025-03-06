<?php

use Eclypsys\MenuSpecial\DishSpecial;

return [
    'pattern' => 'menu/special/dish/(:any)/delete/(:any)',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id, string $pageId) {
        return DishSpecial::delete($id, $pageId);
    }
];
