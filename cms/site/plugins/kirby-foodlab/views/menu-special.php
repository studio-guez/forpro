<?php

use Eclypsys\MenuSpecial;
use Eclypsys\MenuSpecial\DishSpecial;
use Eclypsys\MenuSpecial\WineSpecial;

return [
    "pattern" => "foodlab/restaurant/menu/special",
    "action" => function () {

        $menuSpecial = MenuSpecial::list();
        $wines = WineSpecial::list();
        $dishes = DishSpecial::list();

        return [
            "component" => "k-menu-special-view",
            "props" => [
                "menu" => $menuSpecial,
                "wines" => $wines,
                "dishes" => $dishes,
            ]
        ];
    },
];
