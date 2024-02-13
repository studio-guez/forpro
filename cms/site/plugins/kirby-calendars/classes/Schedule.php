<?php

namespace MediumSans\KirbyCalendars;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Schedule
{
    const FILENAME = 'schedules.json';

    public static function findByCalendarId($id): array
    {
        $schedules = static::list();
        $schedules = array_filter($schedules, function ($schedule) use ($id) {
            return $schedule['calendar_id'] === $id;
        });
        return $schedules;
    }

    /**
     * Creates a new schedule with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {

        $id = uuid();

        $input = [
            'id' => $id,
            'day_id' => $input['day_id'],
            'opening_hour' => $input['opening_hour'] ?? '00:00',
            'closing_hour' => $input['closing_hour'] ?? '00:00',
            'is_closed' => $input['is_closed'] ?? false,
            'calendar_id' => $input['calendar_id'] ?? null,
        ];

        // load all calendars
        $calendars = static::list();

        // set/overwrite the calendar data
        $calendars[$id] = $input;

        return Data::write(static::file(), $calendars);
    }

    /**
     * Deletes a schedule by schedule id
     *
     * @param string $id
     * @return bool
     */
    public static function delete(string $id): bool
    {
        // get all schedules
        $schedules = static::list();

        // remove the schedule from the list
        unset($schedules[$id]);

        // write the update list to the file
        return Data::write(static::file(), $schedules);
    }

    /**
     * Returns the absolute path to the schedules.json
     * This is the place to modify if you don't want to
     * store the schedules in your plugin folder
     * – which you probably really don't want to do.
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/' . static::FILENAME;
    }

    /**
     * Finds a schedule by id and throws an exception
     * if the schedule cannot be found
     *
     * @param string $id
     * @return array
     * @throws NotFoundException
     */
    public static function find(string $id): array
    {
        $schedule = static::list()[$id] ?? null;

        if (empty($schedule) === true) {
            throw new NotFoundException('The schedule could not be found');
        }

        return $schedule;
    }

    /**
     * Lists all schedules from the schedules.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Lists days
     *
     * @return array
     */
    public static function days(): array
    {
        return [
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6,
            'dimanche' => 0,
        ];
    }

    /**
     * Updates a schedule by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @return boolean
     * @throws NotFoundException
     */
    public static function update(string $id, array $input): bool
    {
        $schedule = static::find($id);

        foreach ($input as $key => $value) {
            $schedule[$key] = $value;
        }

        $schedules = static::list();

        $schedules[$id] = $schedule;

        return Data::write(static::file(), $schedules);
    }

    public static function isClosed($id, $day_id): bool
    {
        $schedules = static::findByCalendarId($id);
        $schedule = array_filter($schedules, function ($schedule) use ($day_id) {
            return $schedule['day_id'] === $day_id;
        });
        return $schedule[0]['is_closed'];
    }

    public static function getScheduleStatusForCalendar(int|string $id): array
    {
        $schedules = static::findByCalendarId($id);

        $status = (count($schedules) < 7) ? 'incomplet' : 'complet';
        $theme = ($status === 'incomplet') ? 'error' : 'positive';
        $icon = ($status === 'incomplet') ? 'cancel' : 'check';

        return [
            'status' => $status,
            'theme' => $theme,
            'icon' => $icon
        ];
    }
}
