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
                'mainCoursesShowHide' => Menu\MainCourse::hide(),
                'mainCoursesTitle' => Menu\MainCourse::title(),
                'starters' => $starters,
                'startersShowHide' => Menu\Starter::hide(),
                'startersTitle' => Menu\Starter::title(),
                'desserts' => $desserts,
                'dessertsShowHide' => Menu\Dessert::hide(),
                'dessertsTitle' => Menu\Dessert::title(),
                'softDrinks' => $softdrinks,
                'softDrinksShowHide' => Menu\SoftDrink::hide(),
                'softDrinksTitle' => Menu\SoftDrink::title(),
                'beers' => $beers,
                'beersShowHide' => Menu\Beer::hide(),
                'beersTitle' => Menu\Beer::title(),
                'redWines' => $redWines,
                'redWinesShowHide' => Menu\RedWine::hide(),
                'redWinesTitle' => Menu\RedWine::title(),
                'whiteWines' => $whiteWines,
                'whiteWinesShowHide' => Menu\WhiteWine::hide(),
                'whiteWinesTitle' => Menu\WhiteWine::title(),
                'bubbleWines' => $bubbleWines,
                'bubbleWinesShowHide' => Menu\BubbleWine::hide(),
                'bubbleWinesTitle' => Menu\BubbleWine::title(),
                'cocktails' => $cocktails,
                'cocktailsShowHide' => Menu\Cocktail::hide(),
                'cocktailsTitle' => Menu\Cocktail::title(),
                'hotDrinks' => $hotDrinks,
                'hotDrinksShowHide' => Menu\HotDrink::hide(),
                'hotDrinksTitle' => Menu\HotDrink::title(),
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
