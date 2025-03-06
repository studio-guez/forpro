<?php

use Eclypsys\MenuSpecial\DishSpecial;

return [
    'pattern' => 'menu/special/dish/add/(:any)',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'        => require __DIR__ . '/fields.php',
                'submitButton'  => t('create'),
                'size'          => 'large',
            ],
        ];
    },
    'submit' => function (string $pageId) {
        return DishSpecial::add($pageId, get());
    }
];
