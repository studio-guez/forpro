<?php

use MediumSans\Menu\Dessert;

return [
    'pattern' => 'menu/dessert/(:any)/edit',
    'load'    => function (string $id) {
        $dessert = Dessert::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $dessert
            ]
        ];
    },
    'submit' => function (string $id) {
        return Dessert::update($id, get());
    }
];
