<?php

use Eclypsys\MenuSpecial\WineSpecial;

return [
    'pattern' => 'menu/special/wine/(:any)/delete/(:any)',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id, string $pageId) {
        return WineSpecial::delete($id, $pageId);
    }
];
