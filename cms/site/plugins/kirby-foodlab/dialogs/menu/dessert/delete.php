<?php

use MediumSans\Menu\Dessert;

return [
    'pattern' => 'menu/dessert/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Dessert::delete($id);
    }
];
