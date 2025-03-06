<?php

use Eclypsys\Menu\Whitewine;

return [
    'pattern' => 'menu/whitewine/create',
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
        return Whitewine::create(get());
    }
];
