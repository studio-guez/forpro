<?php

use MediumSans\Menu\Softdrink;

return [
    'pattern' => 'menu/softdrink/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Softdrink::delete($id);
    }
];
