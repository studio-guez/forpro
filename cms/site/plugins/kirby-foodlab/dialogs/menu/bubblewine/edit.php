<?php

use MediumSans\Menu\Bubblewine;

return [
    'pattern' => 'menu/bubblewine/(:any)/edit',
    'load'    => function (string $id) {
        $bubblewine = Bubblewine::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $bubblewine
            ]
        ];
    },
    'submit' => function (string $id) {
        return Bubblewine::update($id, get());
    }
];
