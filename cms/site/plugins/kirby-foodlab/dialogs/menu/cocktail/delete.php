<?php

use Eclypsys\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Cocktail::delete($id);
    }
];
