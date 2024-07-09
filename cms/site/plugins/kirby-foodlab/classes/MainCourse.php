<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class MainCourse extends BaseClass
{
    const FILENAME = "maincourse.json";

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

        $maincourse = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "price" => $input["price"] ?? "",
        ];

        $maincourses = self::list();
        $maincourses[] = $maincourse;

        return Data::write(static::file(), $maincourses);
    }
}
