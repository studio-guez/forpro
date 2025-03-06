<?php

use Eclypsys\MenuSpecial\WineSpecial;

return [
    'pattern' => 'menu/special/wine/(:any)/edit/(:any)',
    'load'    => function (string $id, string $pageId) {
        $wine = WineSpecial::find($id, $pageId);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $wine
            ]
        ];
    },
    'submit' => function (string $id, string $pageId) {
        return WineSpecial::update($id, get(), $pageId);
    }
];
