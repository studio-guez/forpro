<?php

namespace MediumSans\KirbyCalendars;

use Google\Exception;
use Kirby\Cms\User;

class Mail
{
    /**
     * @throws Exception
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

        $f_email = $kirby->option('mediumsans.kirbycalendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirbycalendars.notifications.event_confirmation.name');
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
            throw new Exception($error);
        }

        return $notified;
    }

    /**
     * @throws Exception
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

        $f_email = $kirby->option('mediumsans.kirbycalendars.notifications.from');
        $f_name = $kirby->option('mediumsans.kirbycalendars.notifications.calendar_incharge.name');
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
            throw new Exception($error);
        }

        return $notified;
    }

    private static function createFrom($email, $name): User
    {
        return new User([
            'email' => $email,
            'name' => $name,
        ]);
    }
}
