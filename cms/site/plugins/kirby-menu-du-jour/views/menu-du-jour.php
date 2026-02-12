<?php

use Eclypsys\MenuDuJour\MenuDuJour;

return [
    'pattern' => 'kirby-menu-du-jour',
    'action'  => function () {
        $items = MenuDuJour::list();

        return [
            'component' => 'k-menu-du-jour-view',
            'props' => [
                'items' => $items,
            ]
        ];
    }
];
