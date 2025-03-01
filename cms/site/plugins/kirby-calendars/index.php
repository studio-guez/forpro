<?php

@include_once __DIR__ . '/vendor/autoload.php';

load([
    'MediumSans\KirbyCalendars\Calendar'    => __DIR__ . '/classes/Calendar.php',
    'MediumSans\KirbyCalendars\Schedule'    => __DIR__ . '/classes/Schedule.php',
    'MediumSans\KirbyCalendars\Service'     => __DIR__ . '/classes/Service.php',
    'MediumSans\KirbyCalendars\Database'    => __DIR__ . '/classes/Database.php',
    'MediumSans\KirbyCalendars\Event'       => __DIR__ . '/classes/Event.php',
    'MediumSans\KirbyCalendars\Leave'       => __DIR__ . '/classes/Leave.php',
    'MediumSans\KirbyCalendars\Invitation'  => __DIR__ . '/classes/Invitation.php',
    'MediumSans\KirbyCalendars\Utils'       => __DIR__ . '/classes/Utils.php'
]);

$pluginPermissionNameForBlueprint = 'mediumsans.kirby-calendars';

Kirby::plugin('mediumsans/kirby-calendars', [
    'translations' => require __DIR__ . '/i18n/i18n.php',
    'options' => [
        'notifications' => [
            'from' => 'forpro@mediumsans.studio',
            'calendar_incharge' => [
                'name' => 'Forpro - RDV confirmé',
            ],
            'event_confirmation' => [
                'name' => 'Confirmez votre rendez-vous avec ForPro',
            ],
            'event_remind' => [
                'name' => 'Rappel de votre rendez-vous avec ForPro',
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
                    require __DIR__ . '/views/calendar.php',
                    require __DIR__ . '/views/deniedAccess.php',
                    require __DIR__ . '/views/services.php',
                    require __DIR__ . '/views/schedules.php',
                    require __DIR__ . '/views/events.php',
                    require __DIR__ . '/views/leaves.php',
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
                    // EVENT
                    require __DIR__ . '/dialogs/event/fields.php',
                    require __DIR__ . '/dialogs/event/delete.php',
                    require __DIR__ . '/dialogs/event/share.php',
                    require __DIR__ . '/dialogs/event/edit.php',
                    require __DIR__ . '/dialogs/event/remind.php',
                    // LEAVE
                    require __DIR__ . '/dialogs/leave/fields.php',
                    require __DIR__ . '/dialogs/leave/create.php',
                    require __DIR__ . '/dialogs/leave/delete.php',
                    require __DIR__ . '/dialogs/leave/edit.php',
                ],
            ];
        },
    ],
    'templates' => [
        'emails/event_confirmation.html' => __DIR__ . '/templates/event_confirmation.html.php',
        'emails/event_confirmation.text' => __DIR__ . '/templates/event_confirmation.text.php',
        'emails/calendar_incharge.html'  => __DIR__ . '/templates/calendar_incharge.html.php',
        'emails/calendar_incharge.text'  => __DIR__ . '/templates/calendar_incharge.text.php',
        'emails/event_remind.html'        => __DIR__ . '/templates/event_remind.html.php',
        'emails/event_remind.text'        => __DIR__ . '/templates/event_remind.txt.php',
        'emails/event_rm_assignation.html'        => __DIR__ . '/templates/event_rm_assignation.html.php',
        'emails/event_rm_assignation.text'        => __DIR__ . '/templates/event_rm_assignation.txt.php',
        'emails/event_share.html'        => __DIR__ . '/templates/event_share.html.php',
        'emails/event_share.text'        => __DIR__ . '/templates/event_share.text.php',
    ],
    'routes' => require __DIR__ . '/routes/index.php',
    'hooks' => [
        'panel.route:after' => function ($route, $path, $method) use ($pluginPermissionNameForBlueprint) {
            MediumSans\KirbyCalendars\Utils::checkRoleAccess($route, $path, $method, $pluginPermissionNameForBlueprint);
        }
    ],
]);
