<?php

namespace MediumSans\KirbyCalendars;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Google\Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_AclRule;
use Google_Service_Calendar_AclRuleScope;
use Google_Service_Calendar_Calendar;
use Google_Service_Calendar_Event;
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
        $calendar->setSummary($input['name']);
        $calendar->setDescription($input['description'] ?? '');
        $calendar->setLocation($input['location'] ?? '');

        $createdCalendar = $service->calendars->insert($calendar);

        $aclRule = new Google_Service_Calendar_AclRule();
        $scope = new Google_Service_Calendar_AclRuleScope();

        $scope->setType("default");
        $scope->setValue("");
        $aclRule->setScope($scope);
        $aclRule->setRole("reader");  // "reader" to make it publicly readable

        $service->acl->insert($createdCalendar->getId(), $aclRule);

        $publicUrl = $createdCalendar->getEtag();
        $publicUrlIcal = 'https://www.google.com/calendar/ical/' . $createdCalendar->getId() . '/public/basic.ics';

        $id = uuid();

        $input = [
            'id' => $id,
            'name' => $input['name'] ?? '',
            'description' => $input['description'] ?? '',
            'url' => $publicUrl,
            'ical' => $publicUrlIcal,
            'cid' => $createdCalendar->getId(),
            'etag' => $createdCalendar->getEtag(),
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
     * @throws NotFoundException
     */
    public static function delete(string $id): bool
    {
        $service = static::createGoogleService();
        $calendar = static::find($id);

        // delete the calendar on Google
        $service->calendars->delete($calendar['cid']);

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
     * @throws NotFoundException
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
     * @param string $id
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
     * Retrieves the free slots for a given calendar, service, and date.
     *
     * @param string $calendarId The ID of the calendar.
     * @param string $serviceId The ID of the service.
     * @param string $date The date in the format 'Y-m-d'.
     * @return array An array of DateTimeImmutable objects representing the available slots.
     * @throws Exception
     * @throws NotFoundException
     * @throws \Google\Service\Exception
     * @throws \Exception
     */
    public static function getFreeSlots(string $calendarId, string $serviceId, string $date): array
    {
        $calendar = static::find($calendarId);
        $dayOfWeek = date('l', strtotime($date));
        $dayId = static::getDayIdFromName($dayOfWeek);
        $schedule = Schedule::getByCalendarIdAndDayId($calendarId, $dayId);

        if ($schedule[array_key_first($schedule)]['is_closed']) {
            return [];
        }

        $service = static::createGoogleService();
        $openingTime = new DateTimeImmutable($date . ' ' . $schedule[array_key_first($schedule)]['opening_hour']);
        $closingTime = new DateTimeImmutable($date . ' ' . $schedule[array_key_first($schedule)]['closing_hour']);
        $serviceDuration = static::convertDurationToMinutes(Service::find($serviceId)['duration']);
        $events = static::getEvents($service, $calendar['cid'], $openingTime, $closingTime);

        return static::calculateFreeSlots($openingTime, $closingTime, $serviceDuration, $events);
    }

    /**
     * Creates a new instance of Google_Service_Calendar using Google_Client
     *
     * @return Google_Service_Calendar
     * @throws Exception
     */
    private static function createGoogleService(): Google_Service_Calendar
    {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/forpro-calendars-9f6da95c3aa9.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        return new Google_Service_Calendar($client);
    }

    /**
     * Retrieves a list of events from a Google Calendar
     * within a specified time range
     *
     * @param Google_Service_Calendar $service The Google Calendar service object
     * @param string $cid The ID of the calendar to retrieve events from
     * @param DateTimeImmutable $openingTime The opening time of the time range
     * @param DateTimeImmutable $closingTime The closing time of the time range
     * @return array The list of events within the specified time range
     * @throws \Google\Service\Exception
     */
    private static function getEvents(Google_Service_Calendar $service,
                                      string                  $cid,
                                      DateTimeImmutable       $openingTime,
                                      DateTimeImmutable       $closingTime): array
    {
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => $openingTime->format(DateTimeInterface::RFC3339),
            'timeMax' => $closingTime->format(DateTimeInterface::RFC3339),
        );
        return $service->events->listEvents($cid, $optParams)->getItems();
    }

    /**
     * Calculates the available free slots between the opening and closing times,
     * taking into account the service duration and existing events.
     *
     * @param DateTimeImmutable $openingTime The opening time of the calendar
     * @param DateTimeImmutable $closingTime The closing time of the calendar
     * @param int $serviceDuration The duration of the service in minutes
     * @param array $events An array of existing events
     * @return array An array of available free slots
     * @throws \Exception
     */
    private static function calculateFreeSlots(DateTimeImmutable $openingTime,
                                               DateTimeImmutable $closingTime,
                                               int               $serviceDuration,
                                               array             $events): array
    {
        $availableSlots = [];
        $nextSlot = $openingTime;
        $timezone = $openingTime->getTimezone();

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

    /**
     * Returns the numeric day ID corresponding to the given day name
     *
     * @param string $name The name of the day
     * @return int The numeric day ID
     */
    private static function getDayIdFromName(string $name): int
    {
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];
        return $days[$name];
    }

    /**
     * Converts a duration string in the format "HH:MM" to minutes
     *
     * @param string $duration The duration string in the format "HH:MM"
     * @return int The duration in minutes
     */
    private static function convertDurationToMinutes(string $duration): int
    {
        [$hours, $minutes] = explode(':', $duration);
        return $hours * 60 + $minutes;
    }

    /**
     * @param string $calendarId
     * @param string $serviceId
     * @param string $date
     * @param array $infos
     * @return \Google\Service\Calendar\Event
     * @throws Exception
     * @throws NotFoundException
     * @throws \Google\Service\Exception
     * @throws \Exception
     */
    public static function addEvent(
        string $calendarId,
        string $subjectId,
        string $serviceId,
        string $date,
        array $infos
    ): \Google\Service\Calendar\Event
    {
        $kirby = kirby();
        $subject = $kirby->site()->bookingAppointmentSelect()->toStructure()->toArray()[$subjectId]['label'];

        $googleService = static::createGoogleService();

        $service = Service::find($serviceId);
        $serviceDuration = static::convertDurationToMinutes($service['duration']);
        $serviceTitle = $service['name'];
        $calendar = static::find($calendarId);

        $dateTimeStart = new DateTimeImmutable($date, new DateTimeZone('Europe/Paris'));
        $dateTimeEnd = $dateTimeStart->modify('+' . $serviceDuration . ' minutes');

        $description = $subject
            . "\n"
            . $infos['firstname'] . ' ' . $infos['lastname']
            . "\n"
            . $infos['phone']
            . "\n"
            . $infos['email'];

        $event = static::createGoogleEvent($serviceTitle, $description, $dateTimeStart, $dateTimeEnd);

        Event::create([
            'name' => $serviceTitle,
            'description' => $description,
            'subject' => $subject,
            'date' => $date,
            'start_time' => $dateTimeStart->format('H:i'),
            'end_time' => $dateTimeEnd->format('H:i'),
            'duration' => $service['duration'],
            'service_id' => $serviceId,
            'calendar_id' => $calendarId,
            'eid' => $event->getId(),
            'firstname' => $infos['firstname'],
            'lastname' => $infos['lastname'],
            'phone' => $infos['phone'],
            'email' => $infos['email'],
        ]);

        return $googleService->events->insert($calendar['cid'], $event);
    }

    /**
     * @param string $title
     * @param string $description
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable $end
     * @return Google_Service_Calendar_Event
     */
    private static function createGoogleEvent(string            $title,
                                              string            $description,
                                              DateTimeImmutable $start,
                                              DateTimeImmutable $end): Google_Service_Calendar_Event
    {
        return new Google_Service_Calendar_Event([
            'summary' => $title,
            'description' => $description,
            'start' => [
                'dateTime' => $start->format(DateTimeInterface::RFC3339),
                'timeZone' => 'Europe/Paris',
            ],
            'end' => [
                'dateTime' => $end->format(DateTimeInterface::RFC3339),
                'timeZone' => 'Europe/Paris',
            ]
        ]);
    }
}
