<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class RedWine extends BaseClass
{
    const FILENAME = "redwine.json";

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

        $redWine = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "domain" => $input["domain"] ?? "",
            "mill" => $input["mill"] ?? "",
            "price10cl" => $input["price10cl"] ?? "",
            "price75cl" => $input["price75cl"] ?? "",
        ];

        $redWines = self::list();
        $redWines[] = $redWine;

        return Data::write(static::file(), $redWines);
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

        foreach ($items as &$item) {
            if ($item["id"] === $id) {
                $item = $menu;
                break;
            }
        }

        unset($item);

        return Data::write(static::file(), $items);
    }
}
