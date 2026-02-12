<?php

namespace Eclypsys\MenuDuJour;

use Kirby\Data\Data;

class MenuDuJour extends BaseClass
{
    const FILENAME = "menu-du-jour.json";

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

        $menuDuJour = [
            "id"                    => $id,
            "date"                  => $input["date"] ?? "",
            "station1_cuisine"      => $input["station1_cuisine"] ?? "",
            "station1_menu"         => $input["station1_menu"] ?? "",
            "station1_description"  => $input["station1_description"] ?? "",
            "station1_prix_public"  => $input["station1_prix_public"] ?? "",
            "station1_prix_apprenti" => $input["station1_prix_apprenti"] ?? "",
            "station2_cuisine"      => $input["station2_cuisine"] ?? "",
            "station2_menu"         => $input["station2_menu"] ?? "",
            "station2_description"  => $input["station2_description"] ?? "",
            "station2_prix_public"  => $input["station2_prix_public"] ?? "",
            "station2_prix_apprenti" => $input["station2_prix_apprenti"] ?? "",
            "station3_cuisine"      => $input["station3_cuisine"] ?? "",
            "station3_menu"         => $input["station3_menu"] ?? "",
            "station3_description"  => $input["station3_description"] ?? "",
            "station3_prix_public"  => $input["station3_prix_public"] ?? "",
            "station3_prix_apprenti" => $input["station3_prix_apprenti"] ?? "",
            "station4_cuisine"      => $input["station4_cuisine"] ?? "",
            "station4_menu"         => $input["station4_menu"] ?? "",
            "station4_description"  => $input["station4_description"] ?? "",
            "station4_prix_public"  => $input["station4_prix_public"] ?? "",
            "station4_prix_apprenti" => $input["station4_prix_apprenti"] ?? "",
        ];

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
        $menuDuJour = [
            "id"                    => $id,
            "date"                  => $input["date"] ?? "",
            "station1_cuisine"      => $input["station1_cuisine"] ?? "",
            "station1_menu"         => $input["station1_menu"] ?? "",
            "station1_description"  => $input["station1_description"] ?? "",
            "station1_prix_public"  => $input["station1_prix_public"] ?? "",
            "station1_prix_apprenti" => $input["station1_prix_apprenti"] ?? "",
            "station2_cuisine"      => $input["station2_cuisine"] ?? "",
            "station2_menu"         => $input["station2_menu"] ?? "",
            "station2_description"  => $input["station2_description"] ?? "",
            "station2_prix_public"  => $input["station2_prix_public"] ?? "",
            "station2_prix_apprenti" => $input["station2_prix_apprenti"] ?? "",
            "station3_cuisine"      => $input["station3_cuisine"] ?? "",
            "station3_menu"         => $input["station3_menu"] ?? "",
            "station3_description"  => $input["station3_description"] ?? "",
            "station3_prix_public"  => $input["station3_prix_public"] ?? "",
            "station3_prix_apprenti" => $input["station3_prix_apprenti"] ?? "",
            "station4_cuisine"      => $input["station4_cuisine"] ?? "",
            "station4_menu"         => $input["station4_menu"] ?? "",
            "station4_description"  => $input["station4_description"] ?? "",
            "station4_prix_public"  => $input["station4_prix_public"] ?? "",
            "station4_prix_apprenti" => $input["station4_prix_apprenti"] ?? "",
        ];

        return parent::update($id, $menuDuJour);
    }
}
