<?php

use Eclypsys\MenuSpecial\DishSpecial;

return [
    'pattern' => 'menu/special/dish2/(:any)/edit/(:any)',
    'load'    => function (string $id, string $pageId) {
        $dish = DishSpecial::find($id, $pageId, 'dishes2');

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/../dish/fields.php',
                'value' => $dish
            ]
        ];
    },
    'submit' => function (string $id, string $pageId) {
        return DishSpecial::update($id, get(), $pageId, 'dishes2');
    }
];
