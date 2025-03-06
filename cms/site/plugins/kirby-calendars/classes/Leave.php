<?php

namespace Eclypsys\KirbyCalendars;

use Kirby\Data\Data;

class Leave extends BaseClass
{
    const FILENAME = "leaves.json";

    /**
     * Creates a new leave with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        $id = uuid();

        $event = [
            "name" => $input["name"] ?? "",
            "calendar_id" => $input["calendar_id"],
            "start_datetime" => $input["start_datetime"],
            "end_datetime" => $input["end_datetime"],
        ];

        $leaves = static::list();
        $leaves[$id] = $event;

        return Data::write(static::file(), $leaves);
    }

    /**
     * Updates a leave by id with the given input
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

    /**
     * Get leaves by calendar ID and date.
     *
     * @param string $calendarId The ID of the calendar.
     * @param string $date The date in the format "Y-m-d".
     * @return array The array of leaves matching the calendar ID and date.
     */
    public static function getByCalendarIdAndDate(string $calendarId, string $startDate): array
    {
        $leaves = static::list();
        $leaves = array_filter($leaves, function ($leave) use ($calendarId, $startDate) {
                $startTimestamp = strtotime($startDate);
                $leaveStartTimestamp = strtotime($leave["start_datetime"]);
                $leaveEndTimestamp = strtotime($leave["end_datetime"]);

            return $leave["calendar_id"] === $calendarId && (
                    ($startTimestamp <= $leaveEndTimestamp && $startTimestamp >= $leaveStartTimestamp) ||
                    (date("Y-m-d", $startTimestamp) == date("Y-m-d", $leaveEndTimestamp) || date("Y-m-d", $startTimestamp) == date("Y-m-d", $leaveStartTimestamp))
                );
        });

        return array_values($leaves);
    }
}
