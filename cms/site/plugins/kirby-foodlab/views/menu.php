<?php

use MediumSans\Menu;

return [
    'pattern' => 'foodlab/restaurant/menu',
    'action'  => function () {

        $maincourses = Menu\Maincourse::list();
        $starters = Menu\Starter::list();
        $desserts = Menu\Dessert::list();
        $softdrinks = Menu\Softdrink::list();
        $beers = Menu\Beer::list();
        $redWines = Menu\RedWine::list();
        $whiteWines = Menu\WhiteWine::list();
        $bubbleWines = Menu\BubbleWine::list();
        $cocktails = Menu\Cocktail::list();
        $hotDrinks = Menu\HotDrink::list();

        $menu = Menu::list();

        return [
            'component' => 'k-menu-view',
            'props' => [
                'mainCourses' => $maincourses,
                'starters' => $starters,
                'desserts' => $desserts,
                'softDrinks' => $softdrinks,
                'beers' => $beers,
                'redWines' => $redWines,
                'whiteWines' => $whiteWines,
                'bubbleWines' => $bubbleWines,
                'cocktails' => $cocktails,
                'hotDrinks' => $hotDrinks,
                'textTitle1' => $menu['textTitle1'] ?? "",
                'textSubtitle1' => $menu['textSubtitle1'] ?? "",
                'textContent1' => $menu['textContent1'] ?? "",
                'textTitle2' => $menu['textTitle2'] ?? "",
                'textSubtitle2' => $menu['textSubtitle2'] ?? "",
                'textContent2' => $menu['textContent2'] ?? "",
            ]
        ];
    }
];
