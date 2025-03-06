<?php

use Eclypsys\MenuSpecial\WineSpecial;

return [
    'pattern' => 'menu/special/wine/add/(:any)',
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
        return WineSpecial::add($pageId, get());
    }
];
