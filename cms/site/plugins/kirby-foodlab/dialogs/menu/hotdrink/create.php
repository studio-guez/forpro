<?php

use MediumSans\Menu\Hotdrink;

return [
    'pattern' => 'menu/hotdrink/create',
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
        return Hotdrink::create(get());
    }
];
