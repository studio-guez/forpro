<?php

use Villa1203\MenuDuJour\FoddLab;

return [
    'pattern' => 'fodd-lab/create',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'        => require __DIR__ . '/fields.php',
                'submitButton'  => t('create'),
                'size'          => 'full',
            ],
        ];
    },
    'submit' => function () {
        return FoddLab::create(get());
    }
];
