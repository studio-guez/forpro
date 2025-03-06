<?php

namespace Eclypsys\Menu;

use Kirby\Data\Data;
use Eclypsys\BaseClass;

class Starter extends BaseClass
{
    const FILENAME = "starter.json";

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
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "price" => $input["price"] ?? "",
        ];

        $starters = self::list();
        $starters[] = $starter;

        return Data::write(static::file(), $starters);
    }
}
