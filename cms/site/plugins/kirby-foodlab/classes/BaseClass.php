<?php

namespace Eclypsys;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;
use Eclypsys\Menu\Metadata;

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

    public static function name(): string
    {
        return preg_replace('/\.json$/', "", static::FILENAME);
    }

    public static function title(): string
    {
        return Metadata::get(static::name(), "name");
    }

    public static function hide(): bool
    {
        return Metadata::get(static::name(), "hidden") === true;
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
        foreach ($items as $key => $item) {
            if ($item["id"] === $id) {
                unset($items[$key]);
            }
        }
        return Data::write(static::file(), $items);
    }

    /**
     * Retrieves the list of items
     *
     * @return array
     */
    public static function list(): array
    {
        if (!file_exists(static::file())) {
            return [];
        }
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
            if (is_array($item) && in_array($id, $item)) {
                return $item;
            }
        }

        throw new NotFoundException("The item could not be found");
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

    public static function reorder($newOrder)
    {
        if(!is_array($newOrder) || empty($newOrder) || $newOrder === 'undefined') {
            return true;
        }

        return Data::write(static::file(), $newOrder);
    }
}
