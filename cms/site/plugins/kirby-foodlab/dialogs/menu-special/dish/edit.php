<?php

use Eclypsys\MenuSpecial\DishSpecial;

return [
    'pattern' => 'menu/special/dish/(:any)/edit/(:any)',
    'load'    => function (string $id, string $pageId) {
        $dish = DishSpecial::find($id, $pageId);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $dish
            ]
        ];
    },
    'submit' => function (string $id, string $pageId) {
        return DishSpecial::update($id, get(), $pageId);
    }
];
