<?php

namespace Eclypsys\MenuDuJour;

use Kirby\Data\Data;

class FoddLab extends BaseClass
{
    const FILENAME = "fodd-lab.json";

    /**
     * Creates a new foddLab item with the given $input
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        $id = uuid();

        $item = [
            "id"    => $id,
            "date"  => $input["date"] ?? "",
            "menu"  => $input["menu"] ?? "",
            "prix"  => $input["prix"] ?? "",
        ];

        $items = self::list();
        $items[] = $item;

        return Data::write(static::file(), $items);
    }

    /**
     * Updates a foddLab item by id
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $item = [
            "id"    => $id,
            "date"  => $input["date"] ?? "",
            "menu"  => $input["menu"] ?? "",
            "prix"  => $input["prix"] ?? "",
        ];

        return parent::update($id, $item);
    }
}
