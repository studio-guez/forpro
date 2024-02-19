<?php

@include_once __DIR__ . '/vendor/autoload.php';

load([
    'MediumSans\KirbyCalendars\Calendar' => __DIR__ . '/classes/Calendar.php',
    'MediumSans\KirbyCalendars\Schedule' => __DIR__ . '/classes/Schedule.php',
    'MediumSans\KirbyCalendars\Service' => __DIR__ . '/classes/Service.php',
    'MediumSans\KirbyCalendars\Database' => __DIR__ . '/classes/Database.php',
]);

Kirby::plugin('mediumsans/kirby-calendars', [
    'options' => [
        'notifications' => [
            'from' => 'forpro@mediumsans.studio',
            'calendar_incharge' => [
                'name' => 'Forpro - RDV confirmé',
            ],
            'event_confirmation' => [
                'name' => 'Confirmez votre rendez-vous avec ForPro',
            ],
        ],
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
                    require __DIR__ . '/views/events.php',
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
    'templates' => [
        'emails/event_confirmation.html' => __DIR__ . '/templates/event_confirmation.html.php',
        'emails/event_confirmation.text' => __DIR__ . '/templates/event_confirmation.text.php',
        'emails/calendar_incharge.html' => __DIR__ . '/templates/calendar_incharge.html.php',
        'emails/calendar_incharge.text' => __DIR__ . '/templates/calendar_incharge.text.php',
    ],
    'routes' => require __DIR__ . '/routes/index.php',
]);
