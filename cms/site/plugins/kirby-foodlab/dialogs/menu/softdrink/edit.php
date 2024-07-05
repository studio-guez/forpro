<?php

use MediumSans\Menu\Softdrink;

return [
    'pattern' => 'menu/softdrink/(:any)/edit',
    'load'    => function (string $id) {
        $softdrink = Softdrink::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $softdrink
            ]
        ];
    },
    'submit' => function (string $id) {
        return Softdrink::update($id, get());
    }
];
