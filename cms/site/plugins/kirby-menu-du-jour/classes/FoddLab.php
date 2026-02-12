<?php

namespace Eclypsys\MenuDuJour;

use Kirby\Data\Data;

class FoddLab extends BaseClass
{
    const FILENAME = "fodd-lab.json";

    /**
     * Reads the full data structure from the JSON file.
     * Handles migration from old array format to new object format.
     */
    private static function readAll(): array
    {
        $data = Data::read(static::file());

        // Migration: old format was a plain array of items
        if (array_is_list($data)) {
            return ['items' => $data, 'texte' => ''];
        }

        return $data;
    }

    private static function writeAll(array $data): bool
    {
        return Data::write(static::file(), $data);
    }

    public static function list(): array
    {
        return static::readAll()['items'] ?? [];
    }

    public static function getTexte(): string
    {
        return static::readAll()['texte'] ?? '';
    }

    public static function setTexte(string $texte): bool
    {
        $data = static::readAll();
        $data['texte'] = $texte;
        return static::writeAll($data);
    }

    public static function create(array $input): bool
    {
        $id = uuid();

        $item = [
            "id"    => $id,
            "date"  => $input["date"] ?? "",
            "menu"  => $input["menu"] ?? "",
            "prix"  => $input["prix"] ?? "",
        ];

        $data = static::readAll();
        $data['items'][] = $item;

        return static::writeAll($data);
    }

    public static function update(string $id, array $input): bool
    {
        $item = [
            "id"    => $id,
            "date"  => $input["date"] ?? "",
            "menu"  => $input["menu"] ?? "",
            "prix"  => $input["prix"] ?? "",
        ];

        $data = static::readAll();
        $items = $data['items'] ?? [];

        foreach ($items as &$existingItem) {
            if ($existingItem["id"] === $id) {
                $existingItem = $item;
                break;
            }
        }
        unset($existingItem);

        $data['items'] = $items;
        return static::writeAll($data);
    }

    public static function delete(string $id): bool
    {
        $data = static::readAll();
        $items = $data['items'] ?? [];

        foreach ($items as $key => $item) {
            if ($item["id"] === $id) {
                unset($items[$key]);
            }
        }

        $data['items'] = array_values($items);
        return static::writeAll($data);
    }
}
