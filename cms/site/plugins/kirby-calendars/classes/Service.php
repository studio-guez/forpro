<?php

namespace MediumSans\KirbyCalendars;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Service
{
    const FILENAME = 'services.json';

    /**
     * Finds services by calendar id
     *
     * @param string $id The calendar id to search for
     * @return array An array of services matching the given calendar id
     */
    public static function findByCalendarId(string $id): array
    {
        $services = static::list();
        $services = array_filter($services, function ($service) use ($id) {
            return $service['calendar_id'] === $id;
        });
        return $services;
    }

    /**
     * Creates a new service with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        // reuse the update method to create a new
        // service with the new unique id. If you need different logic
        // here, you can easily extend it
        return static::update(uuid(), $input);
    }

    /**
     * Deletes a service by service id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all services
        $services = static::list();

        // remove the service from the list
        unset($services[$id]);

        // write the update list to the file
        return Data::write(static::file(), $services);
    }

    /**
     * Returns the absolute path to the services.json
     * This is the place to modify if you don't want to
     * store the services in your plugin folder
     * – which you probably really don't want to do.
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/' . static::FILENAME;
    }

    /**
     * Finds a service by id and throws an exception
     * if the service cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $service = static::list()[$id] ?? null;

        if (empty($service) === true) {
            throw new NotFoundException('The service could not be found');
        }

        return $service;
    }

    /**
     * Lists all services from the services.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Updates a service by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $service = [
            'id' => $id,
            'name' => $input['name'] ?? null,
            'duration' => $input['duration'] ?? null,
            'calendar_id' => $input['calendar_id'] ?? null,
        ];

        // load all services
        $services = static::list();

        // set/overwrite the service data
        $services[$id] = $service;

        return Data::write(static::file(), $services);
    }
}
