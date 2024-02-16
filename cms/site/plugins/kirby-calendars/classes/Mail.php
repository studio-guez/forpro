<?php

namespace MediumSans\KirbyCalendars;

use Google\Exception;
use Kirby\Cms\User;

class Mail
{
    /**
     * @throws Exception
     */
    public static function sendConfirmAppointmentNotification(
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

        $name = 'Forpro - Confirmation de rendez-vous';
        $from = static::createFrom($email, $name);

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

    public static function sendAppointmentConfirmationToPersonInCharge(
        string $email,
        string $serviceName,
        string $firstname,
        string $lastname,
        string $startDate,
        string $startTime
    ): bool
    {
        $name = 'Forpro - RDV confirmé';
        $from = static::createFrom($email, $name);

        try {
            $notified = $kirby->email([
                'from' => $from,
                'to' => $email,
                'subject' => 'Nouveau RDV !',
                'template' => 'calendar_incharge',
                'data' => [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'service' => $eventId,
                    'email' => $email,
                    'phone' => $phone,
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

    private static function createFrom($email, $name): User
    {
        $from = new User([
            'email' => $email,
            'name' => $name,
        ]);

        return $from;
    }
}
