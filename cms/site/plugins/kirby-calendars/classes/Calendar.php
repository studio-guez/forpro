<?php

Namespace MediumSans\KirbyCalendars;

use DateTimeImmutable;
use Google\Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_AclRule;
use Google_Service_Calendar_AclRuleScope;
use Google_Service_Calendar_Calendar;
use InvalidArgumentException;
use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Calendar
{
    const FILENAME = 'calendars.json';

    /**
     * Creates a new calendar with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     * @throws Exception
     * @throws \Google\Service\Exception
     */
    public static function create(array $input): bool
    {
        if (!isset($input['name']) || trim($input['name']) === '') {
            throw new InvalidArgumentException('The name must not be empty');
        }

        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/forpro-calendars-9f6da95c3aa9.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $service = new Google_Service_Calendar($client);

        // Create the new calendar on Google
        $calendar = new Google_Service_Calendar_Calendar();
        $calendar->setSummary($input['name'] ?? null);
        $calendar->setDescription($input['description'] ?? null);
        $calendar->setLocation($input['location'] ?? null);

        $createdCalendar = $service->calendars->insert($calendar);

        $aclRule = new Google_Service_Calendar_AclRule();
        $scope = new Google_Service_Calendar_AclRuleScope();

        $scope->setType("default");
        $scope->setValue("");
        $aclRule->setScope($scope);
        $aclRule->setRole("reader");  // "reader" to make it publicly readable

        $service->acl->insert($createdCalendar->getId(), $aclRule);

        $publicUrl = $createdCalendar->getEtag();
        $publicUrlIcal = 'https://www.google.com/calendar/ical/'.$createdCalendar->getId().'/public/basic.ics';

        $id = uuid();

        $input = [
            'id'          => $id,
            'name'        => $input['name'],
            'description' => $input['description'],
            'url'         => $publicUrl,
            'ical'        => $publicUrlIcal,
            'cid'         => $createdCalendar->getId(),
            'etag'        => $createdCalendar->getEtag(),
        ];

        // load all calendars
        $calendars = static::list();

        // set/overwrite the calendar data
        $calendars[$id] = $input;

        return Data::write(static::file(), $calendars);
    }

    /**
     * Deletes a calendar by calendar id
     *
     * @param string $id
     * @return bool
     * @throws Exception
     * @throws \Google\Service\Exception
     */
    public static function delete(string $id): bool
    {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/forpro-calendars-9f6da95c3aa9.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $service = new Google_Service_Calendar($client);

        // delete the calendar on Google
        $service->calendars->delete($id);

        // get all calendars
        $calendars = static::list();

        // remove the calendar from the list
        unset($calendars[$id]);

        // write the updated list to the file
        return Data::write(static::file(), $calendars);
    }

    /**
     * Returns the absolute path to the calendar.json
     * This is the place to modify if you don't want to
     * store the calendar in your plugin folder
     * – which you probably really don't want to do.
     *
     * @return string
     */
    public static function file(): string
    {
        return __DIR__ . '/../data/' . static::FILENAME;
    }

    /**
     * Finds a calendar by id and throws an exception
     * if the calendar cannot be found
     *
     * @param string $id
     * @return array
     */
    public static function find(string $id): array
    {
        $calendar = static::list()[$id] ?? null;

        if (empty($calendar) === true) {
            throw new NotFoundException('The calendar could not be found');
        }

        return $calendar;
    }

    /**
     * Lists all calendar from the calendar.json
     *
     * @return array
     */
    public static function list(): array
    {
        return Data::read(static::file());
    }

    /**
     * Updates a calendar by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param array $input
     * @return boolean
     * @throws NotFoundException
     */
    public static function update(string $id, array $input): bool
    {
        if (!isset($input['name']) || trim($input['name']) === '') {
            throw new InvalidArgumentException('The name must not be empty');
        }

        $calendar = static::find($id);

        foreach ($input as $key => $value) {
            $calendar[$key] = $value;
        }

        $calendars = static::list();

        $calendars[$id] = $calendar;

        return Data::write(static::file(), $calendars);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     * @throws \Google\Service\Exception
     */
    public static function getFreeSlots(string $calendarId, string $serviceId, string $date): array
    {

        if(Schedule::isClosed($calendarId, self::getDaysIdFromName($date))) {
            return [];
        }

        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/forpro-calendars-9f6da95c3aa9.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $service = new Google_Service_Calendar($client);

        $openingTime = new DateTimeImmutable($date . ' 09:00:00');
        $closingTime = new DateTimeImmutable($date . ' 17:00:00');
        $timezone = $openingTime->getTimezone();

        $serviceDuration = Service::find($serviceId)['duration'];

        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $openingTime->format(DateTimeImmutable::RFC3339),
            'timeMax' => $closingTime->format(DateTimeImmutable::RFC3339),
        );

        $events = $service->events->listEvents($calendarId, $optParams)->getItems();

        $availableSlots = [];
        $nextSlot = $openingTime;

        while ($nextSlot <= $closingTime) {
            $overlap = false;
            $slotEnd = $nextSlot->modify('+' . $serviceDuration . ' minutes');

            foreach ($events as $event) {
                $eventStart = new DateTimeImmutable($event->start->dateTime, $timezone);
                $eventEnd = new DateTimeImmutable($event->end->dateTime, $timezone);

                if ($nextSlot < $eventEnd && $slotEnd > $eventStart) {
                    $overlap = true;
                    break;
                }
            }

            if (!$overlap && $slotEnd <= $closingTime) {
                $availableSlots[] = $nextSlot;
            }

            $nextSlot = $nextSlot->modify('+' . $serviceDuration . ' minutes');
        }

        return $availableSlots;
    }

    static function getDayIdFromName($name) {
        $days = [
            'Sun' => 0,
            'Mon' => 1,
            'Tue' => 2,
            'Wed' => 3,
            'Thu' => 4,
            'Fri' => 5,
            'Sat' => 6,
        ];
        return $days[$name];
    }
}
