<?php

use Eclypsys\Restaurant;
use Kirby\Filesystem\F;

return [
    "pattern" => "foodlab/restaurant/content",
    "action" => function () {
        Restaurant::requireEditPermission();

        $modified = F::modified(Restaurant::file());
        $versions = Restaurant::versions();

        return [
            "component" => "k-restaurant-view",
            "props" => [
                // deliberately not named `api`/`versions`: those props drive
                // Kirby's own $panel.content state, which posts to
                // `<api>/changes/…` — a path this view cannot serve, see
                // routes/index.php
                "endpoint" => Restaurant::API_PATH,
                "fields" => Restaurant::fieldProps(),
                "latest" => $versions["latest"],
                "changes" => $versions["changes"],
                "modified" => $modified ? date("c", $modified) : null,
            ],
        ];
    },
];
