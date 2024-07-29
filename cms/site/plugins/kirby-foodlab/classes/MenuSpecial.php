<?php

namespace MediumSans;

use Kirby\Data\Data;

class MenuSpecial extends BaseClass
{
    const FILENAME = "menu-special.json";

    public static function get(bool $renderWithAssets): array
    {
        $menu = self::list();
        $menu['renderWithAssets'] = $renderWithAssets;

        return [
            "menu" => $menu,
        ];
    }

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
            "partnerLogoWidth" => $input["partnerLogoWidth"] ?? "",
            "partnerLogoHeight" => $input["partnerLogoHeight"] ?? "",
            "partnerLogoTop" => $input["partnerLogoTop"] ?? "",
            "partnerLogoLeft" => $input["partnerLogoLeft"] ?? "",
            "partnerLogoRight" => $input["partnerLogoRight"] ?? "",
            "partnerLogoBottom" => $input["partnerLogoBottom"] ?? "",
        ];

        return Data::write(static::file(), $menu);
    }
}
