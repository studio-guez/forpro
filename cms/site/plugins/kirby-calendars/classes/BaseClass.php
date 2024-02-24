<?php

namespace MediumSans\KirbyCalendars;

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

        unset($items[$id]);

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
        $item = static::list()[$id] ?? null;

        if (empty($item) === true) {
            throw new NotFoundException('The item could not be found');
        }

        return $item;
    }

    /**
     * Finds events by calendar id and returns an array of events
     *
     * @param string $id The id of the calendar
     * @return array The array of events that belong to the specified calendar id
     */
    public static function findByCalendarId(string $id): array
    {
        $leaves = static::list();
        return array_filter($leaves, function ($event) use ($id) {
            return $event['calendar_id'] === $id;
        });
    }
}
