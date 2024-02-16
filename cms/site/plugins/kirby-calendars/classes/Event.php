<?php

namespace MediumSans\KirbyCalendars;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Google\Exception;
use Google_Service_Calendar_Event;
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
        return array_filter($events, function ($event) use ($id) {
            return $event['calendar_id'] === $id;
        });
    }

    /**
     * Creates a new event with the given $input
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
            'description' => $input['description'] ?? '',
            'subject' => $input['subject'] ?? '',
            'date' => $input['date'] ?? null,
            'start_time' => $input['start_time'],
            'end_time' => $input['end_time'],
            'duration' => $input['duration'],
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'service_id' => $input['service_id'],
            'calendar_id' => $input['calendar_id'],
            'is_confirmed' => $input['is_confirmed'] ?? false,
        ];

        $events = static::list();
        $events[$id] = $event;

        Data::write(static::file(), $events);

        return $id;
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
        $events = static::list();

        $existingEvent = $events[$id] ?? [];
        $eventToUpdate = array_merge($existingEvent, $input);
        $events[$id] = $eventToUpdate;

        return Data::write(static::file(), $events);
    }

    /**
     * Validates an event by id and returns a boolean value representing the result
     *
     * @param string $id The ID of the event to validate
     * @return array Returns true if the event is valid and successfully updated, 'already_confirmed'
     * if the event is already confirmed, or 'invalid' if the event is not found
     */
    public static function validateEvent(string $id): array
    {
        try {
            $event = static::find($id);
        } catch (NotFoundException $e) {
            return ['state' => 'invalid'];
        }

        if ($event && array_key_exists('is_confirmed', $event)) {
            if ($event['is_confirmed'] === true) {
                $state = 'already_confirmed';
            } else {

                try {
                    static::publish($id);
                } catch (Exception|NotFoundException|\Exception $e) {
                    return ['state' => 'error', 'message' => $e->getMessage()];
                }

                $event['is_confirmed'] = true;
                static::update($id, $event);
                $state = 'confirmed';
            }
        } else {
            $state = 'invalid';
        }

        return ['state' => $state];
    }

    /**
     * Add a new event to the calendar and send a confirmation notification email
     *
     * @param string $calendarId The ID of the calendar to add the event to
     * @param string $subjectId The ID of the subject
     * @param string $serviceId The ID of the service
     * @param string $date The date of the event
     * @param array $infos Additional information for the event (firstname, lastname, phone, email)
     * @return bool True if the event is added successfully and the confirmation email is sent, false otherwise
     * @throws Exception
     * @throws NotFoundException
     * @throws \Exception
     */
    public static function add(
        string $calendarId,
        string $subjectId,
        string $serviceId,
        string $date,
        array  $infos
    ): bool
    {
        $kirby = kirby();
        $subject = $kirby->site()->bookingAppointmentSelect()->toStructure()->toArray()[$subjectId]['label'];

        $service = Service::find($serviceId);
        $serviceDuration = Utils::convertDurationToMinutes($service['duration']);
        $serviceTitle = $service['name'];

        $dateTimeStart = new DateTimeImmutable($date, new DateTimeZone('Europe/Paris'));
        $dateTimeEnd = $dateTimeStart->modify('+' . $serviceDuration . ' minutes');

        $description = $subject
            . "\n"
            . $infos['firstname'] . ' ' . $infos['lastname']
            . "\n"
            . $infos['phone']
            . "\n"
            . $infos['email'];

        $startTime = $dateTimeStart->format('H:i');
        $endTime = $dateTimeEnd->format('H:i');

        $eventId = Event::create([
            'name' => $serviceTitle,
            'description' => $description,
            'subject' => $subject,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $service['duration'],
            'service_id' => $serviceId,
            'calendar_id' => $calendarId,
            'firstname' => $infos['firstname'],
            'lastname' => $infos['lastname'],
            'phone' => $infos['phone'],
            'email' => $infos['email'],
            'is_confirmed' => false,
        ]);

        return Mail::sendPleaseConfirmEventNotification(
            $infos['email'],
            $eventId,
            $serviceTitle,
            $infos['firstname'],
            $infos['lastname'],
            $dateTimeStart->format('d.m.Y'),
            $startTime
        );
    }

    /**
     * Publishes an event with the given eventId to Google Calendar and returns the created Google Event object
     *
     * @param string $eventId The ID of the event to be published
     * @return \Google\Service\Calendar\Event The created Google Event object
     * @throws Exception
     * @throws NotFoundException
     * @throws \Google\Service\Exception
     * @throws \Exception
     */
    private static function publish(string $eventId): \Google\Service\Calendar\Event
    {
        $event = static::find($eventId);
        $calendar = Calendar::find($event['calendar_id']);
        $service = Service::find($event['service_id']);

        $serviceDuration = Utils::convertDurationToMinutes($service['duration']);
        $dateTimeStart = new DateTimeImmutable($event['date']);
        $dateTimeEnd = $dateTimeStart->modify('+' . $serviceDuration . ' minutes');

        $gEvent = static::createGoogleEvent(
            $event['name'],
            $event['description'],
            $dateTimeStart,
            $dateTimeEnd
        );

        Event::update($eventId, ['etag' => $gEvent->getEtag()]);

        Mail::sendEventIsConfirmedToPersonInCharge(
            $calendar,
            $event,
            $service,
            $dateTimeStart->format('d.m.Y'),
            $dateTimeStart->format('H:i')
        );

        $googleService = Utils::createGoogleService();

        return $googleService->events->insert($calendar['cid'], $gEvent);
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
