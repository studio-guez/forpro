<?php

use Eclypsys\Menu\BubbleWine;

return [
    'pattern' => 'menu/bubblewine/create',
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
        return BubbleWine::create(get());
    }
];
