<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class MainCourse extends BaseClass {

    const FILENAME = 'maincourse.json';

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
            "id"            => $id,
            "name"          => $input["name"] ?? "",
            "description"   => $input["description"],
            "price"         => $input["price"],
        ];

        $maincourses = self::list();
        $maincourses[] = $maincourse;

        return Data::write(static::file(), $maincourses);
    }

    /**
     * Updates a menu by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param array $menu
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $schedule = static::find($id);

        foreach ($input as $key => $value) {
            $schedule[$key] = $value;
        }

        $schedules = static::list();

        $schedules[$id] = $schedule;

        return Data::write(static::file(), $schedules);
    }
}
