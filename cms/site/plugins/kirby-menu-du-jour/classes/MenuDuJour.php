<?php

namespace Villa1203\MenuDuJour;

use Kirby\Data\Data;

class MenuDuJour extends BaseClass
{
    const FILENAME = "menu-du-jour.json";

    /**
     * Builds the data array for a 5-day pack from the given input
     */
    private static function buildData(string $id, array $input): array
    {
        $data = [
            "id"            => $id,
            "date"          => $input["date"] ?? "",
            "station1_name" => $input["station1_name"] ?? "",
            "station2_name" => $input["station2_name"] ?? "",
            "station3_name" => $input["station3_name"] ?? "",
            "station4_name" => $input["station4_name"] ?? "",
        ];

        for ($jour = 1; $jour <= 5; $jour++) {
            for ($station = 1; $station <= 4; $station++) {
                foreach (["menu", "description", "prix_public", "prix_apprenti"] as $field) {
                    $key = "jour{$jour}_station{$station}_$field";
                    $data[$key] = $input[$key] ?? "";
                }
            }
        }

        return $data;
    }

    /**
     * Creates a new menu du jour with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        $id = uuid();
        $menuDuJour = self::buildData($id, $input);

        $items = self::list();
        $items[] = $menuDuJour;

        return Data::write(static::file(), $items);
    }

    /**
     * Updates a menu du jour by id with the given input
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $menuDuJour = self::buildData($id, $input);
        return parent::update($id, $menuDuJour);
    }
}
