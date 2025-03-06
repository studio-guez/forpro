<?php

use Eclypsys\Menu\Softdrink;

return [
    'pattern' => 'menu/softdrink/create',
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
        return Softdrink::create(get());
    }
];
