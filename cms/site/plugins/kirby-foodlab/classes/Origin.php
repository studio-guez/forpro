<?php

namespace Eclypsys\Menu;

use Kirby\Data\Data;
use Eclypsys\BaseClass;

class Origin extends BaseClass
{
    const FILENAME = "origin.json";

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

        $origin = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "origin" => $input["origin"] ?? "",
        ];

        $origins = self::list();
        $origins[] = $origin;

        return Data::write(static::file(), $origins);
    }
}
