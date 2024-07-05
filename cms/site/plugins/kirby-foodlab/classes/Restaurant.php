<?php

namespace MediumSans;

use Kirby\Data\Data;

class Restaurant extends BaseClass
{
    const FILENAME = "restaurant.json";

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

        $restaurant = self::list()[0];

        $restaurant = [
            "id" => $id,
            //
            "btnHero1" => $input["btnHero1"] ?? ($restaurant["btnHero1"] ?? ""),
            "btnHero2" => $input["btnHero2"] ?? ($restaurant["btnHero2"] ?? ""),
            "picHero1" => $input["picHero1"] ?? ($restaurant["picHero1"] ?? ""),
            "picHero2" => $input["picHero2"] ?? ($restaurant["picHero2"] ?? ""),
            "picHero3" => $input["picHero3"] ?? ($restaurant["picHero3"] ?? ""),
            "textHero1" =>
                $input["textHero1"] ?? ($restaurant["textHero1"] ?? ""),
            //
            "titleFood" =>
                $input["titleFood"] ?? ($restaurant["titleFood"] ?? ""),
            "textFood" => $input["textFood"] ?? ($restaurant["textFood"] ?? ""),
            "fileFood1" =>
                $input["fileFood1"] ?? ($restaurant["fileFood1"] ?? ""),
            "btnFood" => $input["btnFood"] ?? ($restaurant["btnFood"] ?? ""),
            //
            "titleLab" => $input["titleLab"] ?? ($restaurant["titleLab"] ?? ""),
            "fileLab1" => $input["fileLab1"] ?? ($restaurant["fileLab1"] ?? ""),
            "textLab" => $input["textLab"] ?? ($restaurant["textLab"] ?? ""),
            "btnLab" => $input["btnLab"] ?? ($restaurant["btnLab"] ?? ""),
            //
            "picture1" => $input["picture1"] ?? ($restaurant["picture1"] ?? ""),
            //
            "formation" =>
                $input["formation"] ?? ($restaurant["formation"] ?? ""),
            "titleFormation" =>
                $input["titleFormation"] ??
                ($restaurant["titleFormation"] ?? ""),
            "textFormation" =>
                $input["textFormation"] ?? ($restaurant["textFormation"] ?? ""),
            "fileFormation" =>
                $input["fileFormation"] ?? ($restaurant["fileFormation"] ?? ""),
            "btnFormation" =>
                $input["btnFormation"] ?? ($restaurant["btnFormation"] ?? ""),
            //
            "titleUnivers" =>
                $input["titleUnivers"] ?? ($restaurant["titleUnivers"] ?? ""),
            "subtitleUnivers" =>
                $input["subtitleUnivers"] ??
                ($restaurant["subtitleUnivers"] ?? ""),
            "blogUniversTitle1" =>
                $input["blogUniversTitle1"] ??
                ($restaurant["blogUniversTitle1"] ?? ""),
            "blogUniversFil1" =>
                $input["blogUniversFil1"] ??
                ($restaurant["blogUniversFil1"] ?? ""),
            "blogUniversText1" =>
                $input["blogUniversText1"] ??
                ($restaurant["blogUniversText1"] ?? ""),
            "blogUniversTitle2" =>
                $input["blogUniversTitle2"] ??
                ($restaurant["blogUniversTitle2"] ?? ""),
            "blogUniversFile2" =>
                $input["blogUniversFile2"] ??
                ($restaurant["blogUniversFile2"] ?? ""),
            "blogUniversText2" =>
                $input["blogUniversText2"] ??
                ($restaurant["blogUniversText2"] ?? ""),
            //
            "titleValues" =>
                $input["titleValues"] ?? ($restaurant["titleValues"] ?? ""),
            "textValues" =>
                $input["textValues"] ?? ($restaurant["textValues"] ?? ""),
            "lstValues" =>
                $input["lstValues"] ?? ($restaurant["lstValues"] ?? []),
        ];

        return Data::write(static::file(), $restaurant);
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
