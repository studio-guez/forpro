<?php

namespace MediumSans;

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
        return __DIR__ . '/../data/' . static::FILENAME;
    }

    /**
     * Deletes an event by event id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        $items = static::list();

        $key = array_search($id, $items);

        unset($items[$key]);

        return Data::write(static::file(), $items);
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

        foreach($items as $item) {
            if (is_array($item) && in_array($id, $item)) {
                return $item;
            }
        }

        throw new NotFoundException('The item could not be found');
    }
}
