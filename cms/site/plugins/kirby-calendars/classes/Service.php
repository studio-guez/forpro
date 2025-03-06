<?php

namespace Eclypsys\KirbyCalendars;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Service extends BaseClass
{
    const FILENAME = 'services.json';

    /**
     * Creates a new service with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        return static::update(uuid(), $input);
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
