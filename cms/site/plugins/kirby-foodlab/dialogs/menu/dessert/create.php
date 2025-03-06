<?php

use Eclypsys\Menu\Dessert;

return [
    'pattern' => 'menu/dessert/create',
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
    'submit' => function () {
        return Dessert::create(get());
    }
];
