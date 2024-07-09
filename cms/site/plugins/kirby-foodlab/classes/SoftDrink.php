<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class SoftDrink extends BaseClass {

    const FILENAME = 'softdrink.json';

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

        $softdrink = [
            "id"            => $id,
            "name"          => $input["name"] ?? "",
            "description"   => $input["description"] ?? "",
            "volume"        => $input["volume"] ?? "",
            "price"         => $input["price"] ?? "",
        ];

        $softdrinks = self::list();
        $softdrinks[] = $softdrink;

        return Data::write(static::file(), $softdrinks);
    }
}
