<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class Dessert extends BaseClass
{
    const FILENAME = "dessert.json";

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

        $dessert = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "price" => $input["price"] ?? "",
        ];

        $desserts = self::list();
        $desserts[] = $dessert;

        return Data::write(static::file(), $desserts);
    }
}
