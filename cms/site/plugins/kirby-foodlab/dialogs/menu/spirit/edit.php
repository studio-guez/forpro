<?php

use Eclypsys\Menu\Spirit;

return [
    'pattern' => 'menu/spirit/(:any)/edit',
    'load'    => function (string $id) {
        $spirit = Spirit::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $spirit
            ]
        ];
    },
    'submit' => function (string $id) {
        return Spirit::update($id, get());
    }
];
