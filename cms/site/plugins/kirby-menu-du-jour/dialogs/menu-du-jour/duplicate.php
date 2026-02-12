<?php

use Eclypsys\MenuDuJour\MenuDuJour;

return [
    'pattern' => 'menu-du-jour/(:any)/duplicate',
    'load' => function (string $id) {
        $item = MenuDuJour::find($id);

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
        return MenuDuJour::create(get());
    }
];
