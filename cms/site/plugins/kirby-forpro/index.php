<?php

Kirby::plugin('mediumsans/forpro', [
    'blueprints' => [
        'templates/forpo'       => __DIR__ . '/blueprints/templates/forpro.php',

        'pages/forpro'          => __DIR__ . '/blueprints/pages/forpro.yml',

        'tabs/content'          => __DIR__ . '/blueprints/tabs/content.yml',

        'blocks/animated-list'  => __DIR__ . '/blueprints/blocks/animated-list.yml',
        'blocks/cards'          => __DIR__ . '/blueprints/blocks/cards.yml',
        'blocks/cta'            => __DIR__ . '/blueprints/blocks/cta.yml',
        'blocks/profiles'       => __DIR__ . '/blueprints/blocks/profiles.yml',
        'blocks/quote'          => __DIR__ . '/blueprints/blocks/quote.yml',
        'blocks/capsule'        => __DIR__ . '/blueprints/blocks/capsule.yml',
        'blocks/map'            => __DIR__ . '/blueprints/blocks/map.yml',
    ]
]);
