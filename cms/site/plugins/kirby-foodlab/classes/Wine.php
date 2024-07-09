<?php


namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class Wine extends BaseClass
{
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

        $wine = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "domain" => $input["domain"] ?? "",
            "mill" => $input["mill"] ?? "",
            "price10cl" => $input["price10cl"] ?? "",
            "price50cl" => $input["price50cl"] ?? "",
            "price75cl" => $input["price75cl"] ?? "",
        ];

        $wines = self::list();
        $wines[] = $wine;

        return Data::write(static::file(), $wines);
    }
}
