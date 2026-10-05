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
                // Not `api`/`versions`: those props drive Kirby's $panel.content state, which posts to `<api>/changes/…`, unserved here.
                "endpoint" => Restaurant::API_PATH,
                "fields" => Restaurant::fieldProps(),
                "latest" => $versions["latest"],
                "changes" => $versions["changes"],
                "modified" => $modified ? date("c", $modified) : null,
            ],
        ];
    },
];
