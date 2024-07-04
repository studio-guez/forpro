<?php

use MediumSans\Menu\Hotdrink;

return [
    'pattern' => 'menu/hotdrink/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Hotdrink::delete($id);
    }
];
