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
                'subject' => 'Confirmer votre rendez-vous!',
                'template' => 'event_confirmation',
                'data' => [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'service' => $eventId,
                    'serviceName' => $serviceName,
                    'startDate' => $startDate,
                    'startTime' => $startTime,
                    //todo: erreur lors du passage en prod
                    'validationURL' => 'https://for-pro.ch/confirm/' . $eventId,
                ],
            ])->isSent();
        } catch (Exception $error) {
             $notified = false;
        }

        return $notified;
    }

    /**
     * Sends a remind notification to the specified email address.
     *
     * @param string $email The email address of the recipient.
     * @param string $serviceName The name of the service.
     * @param string $firstname The first name of the recipient.
     * @param string $lastname The last name of the recipient.
     * @param string $startDate The start date of the event.
     * @param string $startTime The start time of the event.
     * @return bool Returns true if the notification is successfully sent, false otherwise.
     */
    public static function sendRemindEventNotification(
        string $email,
        string $serviceName,
        string $firstname,
        string $lastname,
        string $startDate,
        string $startTime
    ): bool
    {
        $kirby = kirby();

        $f_email = $kirby->option('mediumsans.kirby-calendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirby-calendars.notifications.event_remind.name');
        $from = static::createFrom($f_email, $f_name);


        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $email,
                'subject' => 'Rappel pour votre rendez-vous!',
                'template' => 'event_remind',
                'data' => [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'serviceName' => $serviceName,
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
                'subject' => 'Nouveau RDV!',
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
     * Sends a notification email with an event invitation (ICS file) to a specified email address.
     *
     * @param string $email The recipient's email address.
     * @param string $icsContent The content of the ICS file.
     *
     * @return bool  Returns true if the notification email is successfully sent, false otherwise.
     *
     */
    public static function sendEventICS(string $email, string $icsContent): bool
    {
        $kirby = kirby();

        $f_email = $kirby->option('mediumsans.kirby-calendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirby-calendars.notifications.calendar_incharge.name');
        $from = static::createFrom($f_email, $f_name);

        $tempFile = tempnam(sys_get_temp_dir(), 'ics_');
        $tempFileIcs = $tempFile . '.ics';
        rename($tempFile, $tempFileIcs);
        file_put_contents($tempFileIcs, $icsContent);

        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $email,
                'subject' => 'Un rendez-vous a été partagé/attribué',
                'template' => 'event_share',
                'attachments' => [
                    $tempFileIcs
                ],
            ])->isSent();
        } catch (Exception $error) {
            $notified = false;
        }

        return $notified;
    }


    /**
     * Sent a notification if a person is no longer assigned to an event
     *
     * @param string $email The recipient's email address.
     * @param string $startDate The start date of the event.
     * * @param string $startTime The start time of the event.
     *
     * @return bool  Returns true if the notification email is successfully sent, false otherwise.
     *
     */
    public static function sendRemovedAssignation(
        string $email,
        string $startTime,
    ): bool
    {
        $kirby = kirby();

        $f_email = $kirby->option('mediumsans.kirby-calendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirby-calendars.notifications.calendar_incharge.name');
        $from = static::createFrom($f_email, $f_name);

        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $email,
                'subject' => 'Un rendez-vous a été supprimé',
                'template' => 'event_rm_assignation',
                'data' => [
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
