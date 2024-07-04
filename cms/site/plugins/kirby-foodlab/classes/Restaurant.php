<?php

namespace MediumSans;

use Kirby\Data\Data;

class Restaurant extends BaseClass {

    const FILENAME = 'restaurant.json';

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

        $menu = [
            "id"            => $id,
            "starters"      => $input["starters"] ?? "",
            "mainCourses"   => $input["mainCourses"],
            "desserts"      => $input["desserts"],
            "wines"         => $input["wines"],
            "softDrinks"    => $input["softDrinks"],
            "beers"         => $input["beers"],
            "cocktails"     => $input["cocktails"],
            "hotDrinks"     => $input["hotDrinks"],
        ];

        return self::update($menu);
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
        $items = static::list();

        foreach($items as &$item) {
            if ($item['id'] === $id) {
                $item = $menu;
                break;
            }
        }

        unset($item);

        return Data::write(static::file(), $items);
    }
}
