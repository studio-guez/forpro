<?php

use MediumSans\Menu\Hotdrink;

return [
    'pattern' => 'menu/hotdrink/(:any)/edit',
    'load'    => function (string $id) {
        $hotdrink = Hotdrink::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $hotdrink
            ]
        ];
    },
    'submit' => function (string $id) {
        return Hotdrink::update($id, get());
    }
];
