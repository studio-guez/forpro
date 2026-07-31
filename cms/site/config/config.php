<?php

header("Access-Control-Allow-Origin: *");

return [
    "tobimori.seo.canonicalBase" => "https://for-pro.ch",
    "tobimori.seo.lang" => "fr_CH",
    "tobimori.seo.default.metaTemplate" => fn($page) => $page->site()->title()->isNotEmpty()
        ? '{{ title }} - {{ site.title }}'
        : '{{ title }}',
    "url_frontend" => "https://for-pro.ch/",
    'content' => [
        'salt' => getenv('KIRBY_CONTENT_SALT'),
    ],
    'cookie' => [
        'key' => getenv('KIRBY_COOKIE_KEY'),
    ],
    'panel' => [
        'css' => '_custom-panel/main.css',
        'vue' => [
            'compiler' => getenv('KIRBY_VUE_COMPILER') === 'true',
        ],
    ],
    "routes" => [
        [
            "pattern" => "/",
            "action" => function () {
                go("/panel");
            },
        ],
        [
            "pattern" => "booking",
            "method" => "GET",
            "action" => function () {
                $site = kirby()->site();

                return [
                    'headline'              => $site->bookingHeadline()->value(),
                    'bookingIsActive'       => $site->bookingIsActive()->value() == 'true',
                    'description'           => $site->bookingDescription()->kirbytext()->value(),
                    'bandeauInfo'           => $site->bookingBandeauInfo()->value(),
                    'bookingBandeauUrl'     => $site->bookingBandeauUrl()->value(),
                    'bookingServicesLabel'  => $site->bookingServicesLabel()->value(),
                    'bookingSlotsLabel'     => $site->bookingSlotsLabel()->value(),
                    'bookingCalendarLabel'  => $site->bookingCalendarLabel()->value(),
                    'bookingNoSlotsLabel'   => $site->bookingNoSlotsLabel()->value(),
                    'bookingSlotConfirmationLabel'        => $site->bookingSlotConfirmationLabel()->value(),
                    'bookingAppointmentConfirmationLabel' => $site->bookingAppointmentConfirmationLabel()->value(),
                    'bookingAppointmentSuccessLabel'      => $site->bookingAppointmentSuccessLabel()->value(),
                    'bookingAppointmentSelect'            => $site->bookingAppointmentSelect()->toStructure()->toArray(),
                ];
            },
        ],
    ],
    "email" => [
        "transport" => [
            "type" => "smtp",
            "host" => "mail.infomaniak.com",
            "port" => 465,
            "security" => true,
            "auth" => true,
            "username" => "ne-pas-repondre@for-pro.ch",
            "password" => "***REMOVED***",
        ],
    ],
];
