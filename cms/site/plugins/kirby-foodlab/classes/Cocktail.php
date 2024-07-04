<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class Cocktail extends BaseClass {

    const FILENAME = 'cocktail.json';

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

        $hotdrink = [
            "id"            => $id,
            "name"          => $input["name"] ?? "",
            "description"   => $input["description"],
            "volume"        => $input["volume"],
            "price"         => $input["price"],
        ];

        $hotdrinks = self::list();
        $hotdrinks[] = $hotdrink;

        return Data::write(static::file(), $hotdrinks);
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
}
