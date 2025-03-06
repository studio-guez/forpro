<?php

use Eclypsys\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/create',
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
        return Cocktail::create(get());
    }
];
