<?php

use Eclypsys\Menu\Redwine;

return [
    'pattern' => 'menu/redwine/(:any)/edit',
    'load'    => function (string $id) {
        $redwine = Redwine::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $redwine
            ]
        ];
    },
    'submit' => function (string $id) {
        return Redwine::update($id, get());
    }
];
