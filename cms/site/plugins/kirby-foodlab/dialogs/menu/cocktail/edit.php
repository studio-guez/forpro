<?php

use Eclypsys\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/(:any)/edit',
    'load'    => function (string $id) {
        $cocktail = Cocktail::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $cocktail
            ]
        ];
    },
    'submit' => function (string $id) {
        return Cocktail::update($id, get());
    }
];
