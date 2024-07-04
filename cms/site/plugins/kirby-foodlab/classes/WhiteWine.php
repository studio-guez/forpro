<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class WhiteWine extends BaseClass {

    const FILENAME = 'whitewine.json';

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

        $whiteWine = [
            "id"            => $id,
            "name"          => $input["name"] ?? "",
            "description"   => $input["description"],
            "domain"        => $input["domain"],
            "mill"          => $input["mill"],
            "price10cl"     => $input["price10cl"],
            "price75cl"     => $input["price75cl"],
        ];

        $whiteWines = self::list();
        $whiteWines[] = $whiteWine;

        return Data::write(static::file(), $whiteWines);
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
