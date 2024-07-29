<?php

use MediumSans\MenuSpecial;
use MediumSans\MenuSpecial\DishSpecial;
use MediumSans\MenuSpecial\WineSpecial;

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
