<?php

namespace MediumSans;

use Kirby\Data\Data;
use MediumSans\Menu\Beer;
use MediumSans\Menu\BubbleWine;
use MediumSans\Menu\Cocktail;
use MediumSans\Menu\Dessert;
use MediumSans\Menu\HotDrink;
use MediumSans\Menu\MainCourse;
use MediumSans\Menu\RedWine;
use MediumSans\Menu\SoftDrink;
use MediumSans\Menu\Starter;
use MediumSans\Menu\WhiteWine;
use MediumSans\Menu\Metadata;

class Menu extends BaseClass
{
    const FILENAME = "menu.json";

    public static function get(): array
    {
        $starters[] = Starter::list();
        $startersTitle = Starter::title();
        $startersHidden = Starter::hide();

        $mainCourses[] = MainCourse::list();
        $mainCoursesTitle = MainCourse::title();
        $mainCoursesHidden = MainCourse::hide();

        $desserts[] = Dessert::list();
        $dessertsTitle = Dessert::title();
        $dessertsHidden = Dessert::hide();

        $redWines[] = RedWine::list();
        $redWinesTitle = RedWine::title();
        $redWinesHidden = RedWine::hide();

        $whiteWines[] = WhiteWine::list();
        $whiteWinesTitle = WhiteWine::title();
        $whiteWinesHidden = WhiteWine::hide();

        $bubbleWines[] = BubbleWine::list();
        $bubbleWinesTitle = BubbleWine::title();
        $bubbleWinesHidden = BubbleWine::hide();

        $softDrinks[] = SoftDrink::list();
        $softDrinksTitle = SoftDrink::title();
        $softDrinksHidden = SoftDrink::hide();

        $beers[] = Beer::list();
        $beersTitle = Beer::title();
        $beersHidden = Beer::hide();

        $cocktails[] = Cocktail::list();
        $cocktailsTitle = Cocktail::title();
        $cocktailsHidden = Cocktail::hide();

        $hotDrinks[] = HotDrink::list();
        $hotDrinksTitle = HotDrink::title();
        $hotDrinksHidden = HotDrink::hide();

        $menu = self::list();

        $data = [
            "starters" => $starters,
            "startersTitle" => $startersTitle,
            "startersHidden" => $startersHidden,

            "mainCourses" => $mainCourses,
            "mainCoursesTitle" => $mainCoursesTitle,
            "mainCoursesHidden" => $mainCoursesHidden,

            "desserts" => $desserts,
            "dessertsTitle" => $dessertsTitle,
            "dessertsHidden" => $dessertsHidden,

            "redWines" => $redWines,
            "redWinesTitle" => $redWinesTitle,
            "redWinesHidden" => $redWinesHidden,

            "whiteWines" => $whiteWines,
            "whiteWinesTitle" => $whiteWinesTitle,
            "whiteWinesHidden" => $whiteWinesHidden,

            "bubbleWines" => $bubbleWines,
            "bubbleWinesTitle" => $bubbleWinesTitle,
            "bubbleWinesHidden" => $bubbleWinesHidden,

            "softDrinks" => $softDrinks,
            "softDrinksTitle" => $softDrinksTitle,
            "softDrinksHidden" => $softDrinksHidden,

            "beers" => $beers,
            "beersTitle" => $beersTitle,
            "beersHidden" => $beersHidden,

            "cocktails" => $cocktails,
            "cocktailsTitle" => $cocktailsTitle,
            "cocktailsHidden" => $cocktailsHidden,

            "hotDrinks" => $hotDrinks,
            "hotDrinksTitle" => $hotDrinksTitle,
            "hotDrinksHidden" => $hotDrinksHidden,

            "menu" => $menu,

            "pageTitle2" => Metadata::get("page", "title2"),
            "pageTitle3" => Metadata::get("page", "title3"),
            "pageTitle4" => Metadata::get("page", "title4"),

            "page2Order" => Metadata::get("page", "2") ?? "[]",
            "page3Order" => Metadata::get("page", "3") ?? "[]",
            "page4Order" => Metadata::get("page", "4") ?? "[]",
        ];

        return $data;
    }

    /**
     * Creates a new menu with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        $id = uuid();

        $menu = self::list();

        $menu = [
            "id" => $id,
            "textTitle1" => $input["textTitle1"] ?? ($menu["textTitle1"] ?? ""),
            "textSubtitle1" =>
                $input["textSubtitle1"] ?? ($menu["textSubtitle1"] ?? ""),
            "textContent1" =>
                $input["textContent1"] ?? ($menu["textContent1"] ?? ""),
            "textTitle2" => $input["textTitle2"] ?? ($menu["textTitle2"] ?? ""),
            "textSubtitle2" =>
                $input["textSubtitle2"] ?? ($menu["textSubtitle2"] ?? ""),
            "textContent2" =>
                $input["textContent2"] ?? ($menu["textContent2"] ?? ""),
        ];

        return Data::write(static::file(), $menu);
    }

    /**
     * Updates a menu by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $menu
     * @return boolean
     */
    public static function update(string $id, array $menu): bool
    {
        return Data::write(static::file(), $menu);
    }

    public static function reorderData(string $category, array $data): bool
    {
        return match ($category) {
            "starters" => Starter::reorder($data),
            "mainCourses" => MainCourse::reorder($data),
            "desserts" => Dessert::reorder($data),
            "redWines" => RedWine::reorder($data),
            "whiteWines" => WhiteWine::reorder($data),
            "bubbleWines" => BubbleWine::reorder($data),
            "softDrinks" => SoftDrink::reorder($data),
            "beers" => Beer::reorder($data),
            "cocktails" => Cocktail::reorder($data),
            "hotDrinks" => HotDrink::reorder($data),
            default => false,
        };
    }
}
