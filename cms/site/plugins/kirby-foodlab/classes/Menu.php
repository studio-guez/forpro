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

class Menu extends BaseClass
{
    const FILENAME = "menu.json";

    public static function get(): array
    {
        $starters[] = Starter::list();
        $mainCourses[] = MainCourse::list();
        $desserts[] = Dessert::list();
        $redWines[] = RedWine::list();
        $whiteWines[] = WhiteWine::list();
        $bubbleWines[] = BubbleWine::list();
        $softDrinks[] = SoftDrink::list();
        $beers[] = Beer::list();
        $cocktails[] = Cocktail::list();
        $hotDrinks[] = HotDrink::list();
        $menu = self::list();

        $data = [
            "starters" => $starters,
            "mainCourses" => $mainCourses,
            "desserts" => $desserts,
            "redWines" => $redWines,
            "whiteWines" => $whiteWines,
            "bubbleWines" => $bubbleWines,
            "softDrinks" => $softDrinks,
            "beers" => $beers,
            "cocktails" => $cocktails,
            "hotDrinks" => $hotDrinks,
            "menu" => $menu,
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
     * @param array $menu
     * @return boolean
     */
    public static function update(array $menu): bool
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
