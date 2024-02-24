<?php

namespace MediumSans\KirbyCalendars;

use Kirby\Data\Data;

class Leave extends BaseClass
{
    const FILENAME = 'leaves.json';

    /**
     * Creates a new leave with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return string
     */
    public static function create(array $input): string
    {
        $id = uuid();

        $event = [
            'name' => $input['name'] ?? '',
            'start_datetime' => $input['start_datetime'],
            'end_datetime' => $input['end_datetime'],
        ];

        $leaves = static::list();
        $leaves[$id] = $event;

        Data::write(static::file(), $leaves);

        return $id;
    }

    /**
     * Updates an leave by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     */
    public static function update(string $id, array $input): bool
    {
        $leaves = static::list();

        $existingLeave = $leaves[$id] ?? [];
        $leaveToUpdate = array_merge($existingLeave, $input);
        $leaves[$id] = $leaveToUpdate;

        return Data::write(static::file(), $leaves);
    }
}
