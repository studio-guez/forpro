<?php

@include_once __DIR__ . '/vendor/autoload.php';

load([
    'MediumSans\KirbyCalendars\Calendar' => __DIR__ . '/classes/Calendar.php',
    'MediumSans\KirbyCalendars\Schedule' => __DIR__ . '/classes/Schedule.php',
    'MediumSans\KirbyCalendars\Service' => __DIR__ . '/classes/Service.php',
    'MediumSans\KirbyCalendars\Database' => __DIR__ . '/classes/Database.php',
]);

Kirby::plugin('medium-sans/kirby-calendars', [
    'options' => [
        'google_calendar_api_key' => '***REMOVED***',
    ],
    'areas' => [
        'calendars' => function () {
            return [
                'label' => 'Calendriers',
                'icon' => 'calendar',
                'menu' => true,
                'link' => 'kirby-calendars/calendars',
                'view' => 'k-calendars-view',
                'views' => [
                    require __DIR__ . '/views/calendars.php',
                    require __DIR__ . '/views/services.php',
                    require __DIR__ . '/views/schedules.php',
                ],
                'dialogs' => [
                    // CALENDAR
                    require __DIR__ . '/dialogs/calendar/fields.php',
                    require __DIR__ . '/dialogs/calendar/create.php',
                    require __DIR__ . '/dialogs/calendar/delete.php',
                    require __DIR__ . '/dialogs/calendar/edit.php',
                    // SERVICE
                    require __DIR__ . '/dialogs/service/fields.php',
                    require __DIR__ . '/dialogs/service/create.php',
                    require __DIR__ . '/dialogs/service/delete.php',
                    require __DIR__ . '/dialogs/service/edit.php',
                    // SCHEDULE
                    require __DIR__ . '/dialogs/schedule/fields.php',
                    require __DIR__ . '/dialogs/schedule/create.php',
                    require __DIR__ . '/dialogs/schedule/delete.php',
                    require __DIR__ . '/dialogs/schedule/edit.php',
                ],
            ];
        },
    ],
    'api' => require __DIR__ . '/routes/index.php',
]);
