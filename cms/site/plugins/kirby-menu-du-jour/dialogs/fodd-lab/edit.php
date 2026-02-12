<?php

use Eclypsys\MenuDuJour\FoddLab;

return [
    'pattern' => 'fodd-lab/(:any)/edit',
    'load'    => function (string $id) {
        $item = FoddLab::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $item
            ]
        ];
    },
    'submit' => function (string $id) {
        return FoddLab::update($id, get());
    }
];
