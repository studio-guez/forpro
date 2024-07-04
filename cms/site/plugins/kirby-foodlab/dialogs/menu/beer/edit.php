<?php

use MediumSans\Menu\Beer;

return [
    'pattern' => 'menu/beer/(:any)/edit',
    'load'    => function (string $id) {
        $beer = Beer::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $beer
            ]
        ];
    },
    'submit' => function (string $id) {
        return Beer::update($id, get());
    }
];
