<?php

namespace Villa1203\MenuDuJour;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class BaseClass
{
    /**
     * Returns the absolute path to the file that stores the data
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . "/../data/" . static::FILENAME;
    }

    /**
     * Deletes an item by id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        $items = static::list();
        foreach ($items as $key => $item) {
            if ($item["id"] === $id) {
                unset($items[$key]);
            }
        }
        return Data::write(static::file(), array_values($items));
    }

    /**
     * Retrieves the list of items
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Finds an item by its ID.
     *
     * @param string $id The ID of the item to find.
     * @return array The found item.
     * @throws NotFoundException If the item could not be found.
     */
    public static function find(string $id): array
    {
        $items = static::list();

        foreach ($items as $item) {
            if (is_array($item) && isset($item["id"]) && $item["id"] === $id) {
                return $item;
            }
        }

        throw new NotFoundException("The item could not be found");
    }

    /**
     * Updates an item by id with the given input
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $items = static::list();

        foreach ($items as &$item) {
            if ($item["id"] === $id) {
                $item = $input;
                break;
            }
        }

        unset($item);

        return Data::write(static::file(), $items);
    }
}
