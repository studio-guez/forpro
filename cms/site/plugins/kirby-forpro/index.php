<?php

require_once __DIR__ . '/../../../utils/ContentApi.php';

Kirby::plugin('mediumsans/forpro', [
    'blueprints' => [
        'pages/forpro'          => __DIR__ . '/blueprints/pages/forpro.yml',
        'pages/forpro-events'          => __DIR__ . '/blueprints/pages/forpro-events.yml',
        'pages/forpro-event'          => __DIR__ . '/blueprints/pages/forpro-event.yml',

        'pages/short-link'          => __DIR__ . '/blueprints/pages/short-link.yml',
        'pages/short-links-list'          => __DIR__ . '/blueprints/pages/short-links-list.yml',

        'tabs/content'          => __DIR__ . '/blueprints/tabs/content.yml',
        'tabs/booking'          => __DIR__ . '/blueprints/tabs/booking.yml',

        'blocks/cards'            => __DIR__ . '/blueprints/blocks/cards.yml',
        'blocks/cards-focus'      => __DIR__ . '/blueprints/blocks/cards-focus.yml',
        'blocks/cta'              => __DIR__ . '/blueprints/blocks/cta.yml',
        'blocks/profiles'         => __DIR__ . '/blueprints/blocks/profiles.yml',
        'blocks/program'         => __DIR__ . '/blueprints/blocks/program.yml',
        'blocks/quote'            => __DIR__ . '/blueprints/blocks/quote.yml',
        'blocks/capsules'         => __DIR__ . '/blueprints/blocks/capsules.yml',
        'blocks/map'              => __DIR__ . '/blueprints/blocks/map.yml',
        'blocks/body'             => __DIR__ . '/blueprints/blocks/body.yml',
        'blocks/dropdown'         => __DIR__ . '/blueprints/blocks/dropdown.yml',
        'blocks/list'             => __DIR__ . '/blueprints/blocks/list.yml',
        'blocks/graphic-list'             => __DIR__ . '/blueprints/blocks/graphic-list.yml',
        'blocks/google-maps'      => __DIR__ . '/blueprints/blocks/google-maps.yml',
        'blocks/animated-list'    => __DIR__ . '/blueprints/blocks/animated-list.yml',
        'blocks/animated-image'    => __DIR__ . '/blueprints/blocks/animated-image.yml',
        'blocks/logos-list'    => __DIR__ . '/blueprints/blocks/logos-list.yml',
        'blocks/timeline'    => __DIR__ . '/blueprints/blocks/timeline.yml',
        'blocks/listOfPDF'    => __DIR__ . '/blueprints/blocks/listOfPDF.yml',
        'blocks/agenda'    => __DIR__ . '/blueprints/blocks/agenda.yml',

        'files/blocks/image_logo' => __DIR__ . '/blueprints/files/blocks/image_logo.yml',

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
                    'bookingIsActive'       => $kirby->site()->bookingIsActive()->value() == 'true',
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
            [
              'pattern' => 'api/events',
              'method'  => 'GET',
              'action'  => function () use ($kirby) {
                  return [
                    'items' => $kirby->site()->index()->listed()
                        ->filterBy('intendedTemplate', 'event')
                        ->sortBy('dateStart', 'asc')
                        ->map(fn ($page) => ContentApi::event($page))
                        ->values(),
                  ];
              }
            ],
            [
              'pattern' => 'api/event/(:any)',
              'method'  => 'GET',
              'action'  => function (string $slug) use ($kirby) {
                  $page = $kirby->site()->index()->listed()
                      ->filterBy('intendedTemplate', 'event')
                      ->findBy('slug', $slug);

                  if (!$page) return false;

                  return [
                    'pageInfo' => ContentApi::event($page),
                    'seo'      => ContentApi::seo($page, $kirby->site()),
                  ];
              }
            ],
            [
              'pattern' => 'api/projects',
              'method'  => 'GET',
              'action'  => function () use ($kirby) {
                  return [
                    'items' => $kirby->site()->index()->listed()
                        ->filterBy('intendedTemplate', 'project')
                        ->map(fn ($page) => ContentApi::project($page))
                        ->values(),
                  ];
              }
            ],
            [
              'pattern' => 'api/project/(:any)',
              'method'  => 'GET',
              'action'  => function (string $slug) use ($kirby) {
                  $page = $kirby->site()->index()->listed()
                      ->filterBy('intendedTemplate', 'project')
                      ->findBy('slug', $slug);

                  if (!$page) return false;

                  return [
                    'pageInfo' => ContentApi::project($page),
                    'seo'      => ContentApi::seo($page, $kirby->site()),
                  ];
              }
            ],
        ];
    },
]);
