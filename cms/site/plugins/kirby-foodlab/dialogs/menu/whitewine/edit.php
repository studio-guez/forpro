<?php

use Eclypsys\Menu\Whitewine;

return [
    'pattern' => 'menu/whitewine/(:any)/edit',
    'load'    => function (string $id) {
        $whitewine = Whitewine::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $whitewine
            ]
        ];
    },
    'submit' => function (string $id) {
        return Whitewine::update($id, get());
    }
];
