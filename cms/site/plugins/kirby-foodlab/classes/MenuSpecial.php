<?php

namespace MediumSans;

use Kirby\Data\Data;

class MenuSpecial extends BaseClass
{
    const FILENAME = "menu-special.json";

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

        $menu = [
            "id"            => $id,
            "pages"         => $input["pages"] ?? [],
            "textInfo"      => $input["textInfo"] ?? "",
            "partnerLogo"   => $input["partnerLogo"] ?? "",
        ];

        return Data::write(static::file(), $menu);
    }
}
