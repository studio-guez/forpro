<?php

use Eclypsys\Menu\Redwine;

return [
    'pattern' => 'menu/redwine/create',
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
        return Redwine::create(get());
    }
];
