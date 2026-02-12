<?php

use Eclypsys\MenuDuJour\MenuDuJour;

return [
    'pattern' => 'menu-du-jour/(:any)/edit',
    'load'    => function (string $id) {
        $menuDuJour = MenuDuJour::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $menuDuJour
            ]
        ];
    },
    'submit' => function (string $id) {
        return MenuDuJour::update($id, get());
    }
];
