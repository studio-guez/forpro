<?php

Kirby::plugin('mediumsans/forpro', [
    'blueprints' => [
        'pages/forpro'          => __DIR__ . '/blueprints/pages/forpro.yml',

        'tabs/content'          => __DIR__ . '/blueprints/tabs/content.yml',
        'tabs/booking'          => __DIR__ . '/blueprints/tabs/booking.yml',

        'blocks/cards'            => __DIR__ . '/blueprints/blocks/cards.yml',
        'blocks/cards-focus'      => __DIR__ . '/blueprints/blocks/cards-focus.yml',
        'blocks/cta'              => __DIR__ . '/blueprints/blocks/cta.yml',
        'blocks/profiles'         => __DIR__ . '/blueprints/blocks/profiles.yml',
        'blocks/quote'            => __DIR__ . '/blueprints/blocks/quote.yml',
        'blocks/capsules'         => __DIR__ . '/blueprints/blocks/capsules.yml',
        'blocks/map'              => __DIR__ . '/blueprints/blocks/map.yml',
        'blocks/body'             => __DIR__ . '/blueprints/blocks/body.yml',
        'blocks/dropdown'         => __DIR__ . '/blueprints/blocks/dropdown.yml',
        'blocks/list'             => __DIR__ . '/blueprints/blocks/list.yml',
        'blocks/google-maps'      => __DIR__ . '/blueprints/blocks/google-maps.yml',
        'blocks/animated-list'    => __DIR__ . '/blueprints/blocks/animated-list.yml',
        'blocks/logos-list'    => __DIR__ . '/blueprints/blocks/logos-list.yml',
        'blocks/timeline'    => __DIR__ . '/blueprints/blocks/timeline.yml',
        'blocks/listeOfPDF'    => __DIR__ . '/blueprints/blocks/listeOfPDF.yml',
    ],
    'templates' => [
        'forpro'                => __DIR__ . '/templates/forpro.php',
        'forpro'                => __DIR__ . '/templates/forpro.json.php',
    ],
      'routes' => function ($kirby) {
        return [
            [
              'pattern' => 'booking',
              'method'  => 'GET',
              'action'  => function () use ($kirby) {
                  return [
                    'headline'              => $kirby->site()->bookingHeadline()->value(),
                    'description'           => $kirby->site()->bookingDescription()->kirbytext()->value(),
                    'bandeauInfo'           => $kirby->site()->bookingBandeauInfo()->value(),
                    'bookingBandeauUrl'           => $kirby->site()->bookingBandeauUrl()->value(),
                    'bookingServicesLabel'  => $kirby->site()->bookingServicesLabel()->value(),
                    'bookingSlotsLabel'     => $kirby->site()->bookingSlotsLabel()->value(),
                    'bookingCalendarLabel'  => $kirby->site()->bookingCalendarLabel()->value(),
                    'bookingNoSlotsLabel'   => $kirby->site()->bookingNoSlotsLabel()->value(),
                    'bookingSlotConfirmationLabel'        => $kirby->site()->bookingSlotConfirmationLabel()->value(),
                    'bookingAppointmentConfirmationLabel' => $kirby->site()->bookingAppointmentConfirmationLabel()->value(),
                    'bookingAppointmentSuccessLabel'      => $kirby->site()->bookingAppointmentSuccessLabel()->value(),
                    'bookingAppointmentSelect'            => $kirby->site()->bookingAppointmentSelect()->toStructure()->toArray()
                  ];
              }
            ],
        ];
    },
]);
