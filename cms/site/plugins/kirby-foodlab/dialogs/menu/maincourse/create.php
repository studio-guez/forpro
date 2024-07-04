<?php

use MediumSans\Menu\Maincourse;

return [
    'pattern' => 'menu/maincourse/create',
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
        return Maincourse::create(get());
    }
];
