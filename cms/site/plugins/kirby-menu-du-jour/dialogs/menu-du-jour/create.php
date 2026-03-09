<?php

use Villa1203\MenuDuJour\MenuDuJour;

return [
    'pattern' => 'menu-du-jour/create',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'        => require __DIR__ . '/fields.php',
                'submitButton'  => t('create'),
                'size' => 'full',
            ],
        ];
    },
    'submit' => function () {
        return MenuDuJour::create(get());
    }
];
