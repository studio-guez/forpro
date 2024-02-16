<?php

namespace MediumSans\KirbyCalendars;

use Kirby\Exception\Exception;
use Kirby\Cms\User;

class Mail
{
    /**
     * Sends a notification to the specified email address to confirm an event.
     *
     * @param string $email The email address of the recipient.
     * @param string $eventId The ID of the event.
     * @param string $serviceName The name of the service.
     * @param string $firstname The first name of the recipient.
     * @param string $lastname The last name of the recipient.
     * @param string $startDate The start date of the event.
     * @param string $startTime The start time of the event.
     * @return bool Returns true if the notification is successfully sent, false otherwise.
     */
    public static function sendPleaseConfirmEventNotification(
        string $email,
        string $eventId,
        string $serviceName,
        string $firstname,
        string $lastname,
        string $startDate,
        string $startTime
    ): bool
    {
        $kirby = kirby();

        $f_email = $kirby->option('mediumsans.kirby-calendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirby-calendars.notifications.event_confirmation.name');
        $from = static::createFrom($f_email, $f_name);

        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $email,
                'subject' => 'Confirmer votre rendez-vous !',
                'template' => 'event_confirmation',
                'data' => [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'service' => $eventId,
                    'serviceName' => $serviceName,
                    'startDate' => $startDate,
                    'startTime' => $startTime,
                    'validationURL' => 'https://forpro-website.sdrvl.ch/confirm/' . $eventId,
                ],
            ])->isSent();
        } catch (Exception $error) {
             $notified = false;
        }

        return $notified;
    }

    /**
     * Sends a notification email to the person in charge of a calendar event to confirm the event.
     *
     * @param array $calendar The calendar details.
     * @param array $event The event details.
     * @param array $service The service details.
     * @param string $startDate The start date of the event (YYYY-MM-DD format).
     * @param string $startTime The start time of the event (HH:MM AM/PM format).
     *
     * @return bool  Returns true if the notification email is successfully sent, false otherwise.
     *
     */
    public static function sendEventIsConfirmedToPersonInCharge(
        array  $calendar,
        array  $event,
        array  $service,
        string $startDate,
        string $startTime
    ): bool
    {
        $kirby = kirby();

        $f_email = $kirby->option('mediumsans.kirby-calendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirby-calendars.notifications.calendar_incharge.name');
        $from = static::createFrom($f_email, $f_name);

        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $calendar['email'],
                'subject' => 'Nouveau RDV !',
                'template' => 'calendar_incharge',
                'data' => [
                    'firstname' => $event['firstname'],
                    'description' => $event['description'],
                    'lastname' => $event['lastname'],
                    'email' => $event['email'],
                    'phone' => $event['phone'],
                    'serviceName' => $service['name'],
                    'startDate' => $startDate,
                    'startTime' => $startTime,
                ],
            ])->isSent();
        } catch (Exception $error) {
            $notified = false;
        }

        return $notified;
    }

    /**
     * Creates a new User instance based on the given email and name.
     *
     * @param string $email The email of the user.
     * @param string $name The name of the user.
     *
     * @return User  The newly created User instance.
     */
    private static function createFrom(string $email, string $name): User
    {
        return new User([
            'email' => $email,
            'name' => $name,
        ]);
    }
}
