<?php

namespace MediumSans\KirbyCalendars;

use Google\Exception;
use Google_Client;
use Google_Service_Calendar;

class Utils
{
    /**
     * Converts a duration string in the format "HH:MM" to minutes
     *
     * @param string $duration The duration string in the format "HH:MM"
     * @return int The duration in minutes
     */
    public static function convertDurationToMinutes(string $duration): int
    {
        [$hours, $minutes] = explode(':', $duration);
        return $hours * 60 + $minutes;
    }

    /**
     * Creates a new instance of Google_Service_Calendar using Google_Client
     *
     * @return Google_Service_Calendar
     * @throws Exception
     */
    public static function createGoogleService(): Google_Service_Calendar
    {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../config/forpro-calendars-9f6da95c3aa9.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        return new Google_Service_Calendar($client);
    }
}
