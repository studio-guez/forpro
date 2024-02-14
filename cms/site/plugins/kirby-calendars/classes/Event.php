<?php

namespace MediumSans\KirbyCalendars;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Event
{
    const FILENAME = 'events.json';

    /**
     * Finds events by calendar id and returns an array of events
     *
     * @param string $id The id of the calendar
     * @return array The array of events that belong to the specified calendar id
     */
    public static function findByCalendarId(string $id): array
    {
        $events = static::list();
        $events = array_filter($events, function ($event) use ($id) {
            return $event['calendar_id'] === $id;
        });
        return $events;
    }

    /**
     * Creates a new event with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        // reuse the update method to create a new
        // event with the new unique id. If you need different logic
        // here, you can easily extend it
        return static::update(uuid(), $input);
    }

    /**
     * Deletes an event by event id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all events
        $events = static::list();

        // remove the event from the list
        unset($events[$id]);

        // write the update list to the file
        return Data::write(static::file(), $events);
    }

    /**
     * Returns the absolute path to the events.json
     * This is the place to modify if you don't want to
     * store the events in your plugin folder
     * – which you probably really don't want to do.
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/' . static::FILENAME;
    }

    /**
     * Finds an event by id and throws an exception
     * if the event cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $event = static::list()[$id] ?? null;

        if (empty($event) === true) {
            throw new NotFoundException('The event could not be found');
        }

        return $event;
    }

    /**
     * Lists all events from the events.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Updates an event by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $event = [
            'name' => $input['name'],
            'description' => $input['description'] ?? '',
            'date' => $input['date'] ?? null,
            'start_time' => $input['start_time'],
            'end_time' => $input['end_time'],
            'duration' => $input['duration'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'service_id' => $input['service_id'],
            'calendar_id' => $input['calendar_id'],
            'eid' => $input['eid'],
        ];

        // load all events
        $events = static::list();

        // set/overwrite the event data
        $events[$id] = $event;

        return Data::write(static::file(), $events);
    }
}
