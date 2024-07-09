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
                'mainCoursesShowHide' => Menu\Metadata::get(Menu\MainCourse::name(), 'hidden') == 1,
                'mainCoursesTitle' => Menu\Metadata::get(Menu\MainCourse::name(), 'name') ?? "",
                'starters' => $starters,
                'startersShowHide' => Menu\Metadata::get(Menu\Starter::name(), 'hidden') == 1,
                'startersTitle' => Menu\Metadata::get(Menu\Starter::name(), 'name') ?? "",
                'desserts' => $desserts,
                'dessertsShowHide' => Menu\Metadata::get(Menu\Dessert::name(), 'hidden') == 1,
                'dessertsTitle' => Menu\Metadata::get(Menu\Dessert::name(), 'name') ?? "",
                'softDrinks' => $softdrinks,
                'softDrinksShowHide' => Menu\Metadata::get(Menu\SoftDrink::name(), 'hidden') == 1,
                'softDrinksTitle' => Menu\Metadata::get(Menu\SoftDrink::name(), 'name') ?? "",
                'beers' => $beers,
                'beersShowHide' => Menu\Metadata::get(Menu\Beer::name(), 'hidden'),
                'beersTitle' => Menu\Metadata::get(Menu\Beer::name(), 'name') ?? "",
                'redWines' => $redWines,
                'redWinesShowHide' => Menu\Metadata::get(Menu\RedWine::name(), 'hidden'),
                'redWinesTitle' => Menu\Metadata::get(Menu\RedWine::name(), 'name') ?? "",
                'whiteWines' => $whiteWines,
                'whiteWinesShowHide' => Menu\Metadata::get(Menu\WhiteWine::name(), 'hidden'),
                'whiteWinesTitle' => Menu\Metadata::get(Menu\WhiteWine::name(), 'name') ?? "",
                'bubbleWines' => $bubbleWines,
                'bubbleWinesShowHide' => Menu\Metadata::get(Menu\BubbleWine::name(), 'hidden'),
                'bubbleWinesTitle' => Menu\Metadata::get(Menu\BubbleWine::name(), 'name') ?? "",
                'cocktails' => $cocktails,
                'cocktailsShowHide' => Menu\Metadata::get(Menu\Cocktail::name(), 'hidden'),
                'cocktailsTitle' => Menu\Metadata::get(Menu\Cocktail::name(), 'name') ?? "",
                'hotDrinks' => $hotDrinks,
                'hotDrinksShowHide' => Menu\Metadata::get(Menu\HotDrink::name(), 'hidden'),
                'hotDrinksTitle' => Menu\Metadata::get(Menu\HotDrink::name(), 'name') ?? "",
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
