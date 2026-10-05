<?php

namespace Villa1203\MenuDuJour;

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
        if (file_exists(static::file()) === false) {
            return ['items' => [], 'texte' => ''];
        }

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
        $data['texte'] = static::sanitizeHtml($texte);
        return static::writeAll($data);
    }

    /**
     * Builds a week's item from the given input
     */
    private static function buildData(string $id, array $input): array
    {
        $data = [
            "id"   => $id,
            "date" => $input["date"] ?? "",
            "prix" => $input["prix"] ?? "",
        ];

        for ($jour = 1; $jour <= 6; $jour++) {
            $data["jour{$jour}_menu"] = static::sanitizeHtml($input["jour{$jour}_menu"] ?? "");
        }

        return $data;
    }

    public static function create(array $input): bool
    {
        $item = self::buildData(uuid(), $input);

        $data = static::readAll();
        $data['items'][] = $item;

        return static::writeAll($data);
    }

    public static function update(string $id, array $input): bool
    {
        $item = self::buildData($id, $input);

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
