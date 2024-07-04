<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class Starter extends BaseClass {

    const FILENAME = 'starter.json';

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

        $starter = [
            "id"            => $id,
            "name"          => $input["name"] ?? "",
            "description"   => $input["description"],
            "price"         => $input["price"],
        ];

        $starters = self::list();
        $starters[] = $starter;

        return Data::write(static::file(), $starters);
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
