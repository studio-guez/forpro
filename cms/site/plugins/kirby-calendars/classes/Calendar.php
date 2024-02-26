<?php

namespace MediumSans\KirbyCalendars;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Google\Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_AclRule;
use Google_Service_Calendar_AclRuleScope;
use Google_Service_Calendar_Calendar;
use InvalidArgumentException;
use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;

class Calendar extends BaseClass
{
    const FILENAME = 'calendars.json';
    const MINUTES_MODIFICATION = '+%d minutes';

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

        $service = Utils::createGoogleService();

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
        $aclRule->setRole("reader");

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

        $calendars = static::list();
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
        $service = Utils::createGoogleService();
        $calendar = static::find($id);

        $service->calendars->delete($calendar['cid']);

        $calendars = static::list();

        unset($calendars[$id]);

        return Data::write(static::file(), $calendars);
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
        $leaves = Leave::getByCalendarIdAndDate($calendarId, $date);

        if ($schedule[array_key_first($schedule)]['is_closed']) {
            return [];
        }

        $service = Utils::createGoogleService();
        $openingTime = new DateTimeImmutable($date . ' ' . $schedule[array_key_first($schedule)]['opening_hour']);
        $closingTime = new DateTimeImmutable($date . ' ' . $schedule[array_key_first($schedule)]['closing_hour']);
        $serviceDuration = Utils::convertDurationToMinutes(Service::find($serviceId)['duration']);
        $events = static::getEvents($service, $calendar['cid'], $openingTime, $closingTime);
        $maxEventsPerDay = static::getMaxEventsPerDay($calendarId);

        return static::calculateFreeSlots($openingTime,
            $closingTime,
            $serviceDuration,
            $leaves,
            $events,
            $maxEventsPerDay);
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
     * Determines if the given time slot overlaps with any items in the array
     *
     * @param array $items An array of items to check for overlap
     * @param DateTimeImmutable $nextSlotStart The start time of the next time slot
     * @param DateTimeImmutable $slotEnd The end time of the current time slot
     * @param DateTimeZone $timezone The timezone to use for date/time comparisons
     * @return bool True if the time slot overlaps with any items, false otherwise
     * @throws \Exception
     */
    private static function overlapsWith(array $items,
                                         DateTimeImmutable $nextSlotStart,
                                         DateTimeImmutable $slotEnd,
                                         DateTimeZone $timezone): bool
    {
        foreach ($items as $item) {
            $itemStart = new DateTimeImmutable($item->getStart()->dateTime, $timezone);
            $itemEnd = new DateTimeImmutable($item->getEnd()->dateTime, $timezone);
            if ($slotEnd > $itemStart && $nextSlotStart < $itemEnd || $nextSlotStart === $itemStart) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks whether the number of events exceeds the maximum events per day
     *
     * @param array $events The array of events
     * @param int $maxEventsPerDay The maximum number of events per day
     * @return bool Returns true if the number of events exceeds the maximum, false otherwise
     */
    private static function exceedsMaxEventsPerDay(array $events, int $maxEventsPerDay): bool
    {
        return count($events) > $maxEventsPerDay;
    }

    /**
     * Calculates the start of the next time slot based on the current time slot start and the service duration
     *
     * @param DateTimeImmutable $nextSlotStart The start time of the current time slot
     * @param int $serviceDuration The duration of the service in minutes
     * @return DateTimeImmutable The start time of the next time slot
     */
    private static function calculateNextSlotStart(DateTimeImmutable $nextSlotStart,
                                                   int               $serviceDuration): DateTimeImmutable
    {
        return $nextSlotStart->modify(sprintf(self::MINUTES_MODIFICATION, $serviceDuration));
    }

    /**
     * Calculates the available time slots for appointments based on the given parameters
     *
     * @param DateTimeImmutable $openingTime The start time of the available time range
     * @param DateTimeImmutable $closingTime The end time of the available time range
     * @param int $serviceDuration The duration of each appointment in minutes
     * @param array $leaves An array of leave time ranges in which appointments are not allowed
     * @param array $events An array of existing appointments
     * @param int $maxEventsPerDay The maximum number of appointments allowed per day
     * @return array An array of DateTimeImmutable objects representing the available time slots
     * @throws \Exception
     */
    private static function calculateFreeSlots(DateTimeImmutable $openingTime,
                                               DateTimeImmutable $closingTime,
                                               int               $serviceDuration,
                                               array             $leaves,
                                               array             $events,
                                               int               $maxEventsPerDay): array
    {
        $availableSlots = [];
        $nextSlotStart = $openingTime;
        $timezone = new DateTimeZone('Europe/Zurich');

        if(self::exceedsMaxEventsPerDay($events, $maxEventsPerDay)) {
            return [];
        }

        while ($nextSlotStart < $closingTime) {
            $slotEnd = self::calculateNextSlotStart($nextSlotStart, $serviceDuration);

            if (!self::overlapsWith($events, $nextSlotStart, $slotEnd, $timezone) &&
                !self::overlapsWith($leaves, $nextSlotStart, $slotEnd, $timezone) &&
                $slotEnd <= $closingTime) {
                $availableSlots[] = $nextSlotStart;
            }

            $nextSlotStart = self::calculateNextSlotStart($nextSlotStart, $serviceDuration);
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
     * Returns the maximum number of events per day for a given calendar.
     *
     * @param string $calendarId The ID of the calendar.
     * @return int The maximum number of events per day for the calendar.
     * @throws NotFoundException If the calendar with the given ID is not found.
     */
    private static function getMaxEventsPerDay(string $calendarId): int
    {
        $calendar = static::find($calendarId);
        return $calendar['max_events_per_day'];
    }

    /**
     * Retrieves the minimum number of days before a rendezvous from the calendar
     *
     * @param string $calendarId The ID of the calendar
     * @return int The minimum number of days before a rendezvous from the calendar
     * @throws NotFoundException
     */
    public static function getMinDaysBeforeRdvs(string $calendarId): int
    {
        $calendar = static::find($calendarId);
        return $calendar['min_days_before_rdvs'];
    }

    /**
     * Retrieves the options for a given calendar.
     *
     * @param string $calendarId The ID of the calendar.
     * @return array An associative array containing the options for the calendar. The array
     *               structure is as follows:
     *               [
     *                  'minDaysBeforeRdvs' => The minimum number of days before an appointment,
     *                                        as set in the calendar. (integer)
     *                  'maxEventsPerDay' => The maximum number of events allowed per day,
     *                                      as set in the calendar. (integer)
     *               ]
     * @throws NotFoundException
     */
    public static function getOptions(string $calendarId): array
    {
        $calendar = static::find($calendarId);
        return [
            'minDaysBeforeRdvs' => $calendar['min_days_before_rdvs'],
            'maxEventsPerDay' => $calendar['max_events_per_day'],
        ];
    }
}
