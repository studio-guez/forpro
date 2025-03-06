<?php

use Eclypsys\Menu\Starter;

return [
    'pattern' => 'menu/starter/(:any)/edit',
    'load'    => function (string $id) {
        $starter = Starter::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $starter
            ]
        ];
    },
    'submit' => function (string $id) {
        return Starter::update($id, get());
    }
];
