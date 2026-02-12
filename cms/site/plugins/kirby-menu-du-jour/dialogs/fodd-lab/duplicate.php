<?php

use Eclypsys\MenuDuJour\FoddLab;

return [
    'pattern' => 'fodd-lab/(:any)/duplicate',
    'load' => function (string $id) {
        $item = FoddLab::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'submitButton' => t('create'),
                'size' => 'large',
                'value' => $item
            ]
        ];
    },
    'submit' => function (string $id) {
        return FoddLab::create(get());
    }
];
