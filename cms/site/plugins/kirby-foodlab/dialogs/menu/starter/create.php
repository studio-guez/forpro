<?php

use MediumSans\Menu\Starter;

return [
    'pattern' => 'menu/starter/create',
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
        return Starter::create(get());
    }
];
