<?php

use Eclypsys\Restaurant;

/**
 * `restaurantlink` field type.
 *
 * Kirby's `link` field with one extra type on the panel side: `media`, which
 * picks a file from the restaurant media library (data/restaurant-media) and
 * stores its path. It replaces the native `file` type, which can only resolve
 * files that belong to a Kirby model.
 *
 * The stored value stays a root-relative path so the content is portable
 * between environments; `Restaurant::toApi()` makes it absolute.
 */
return [
    "extends" => "link",
    "props" => [
        /**
         * Prefix the panel matches a stored value against to recognise a
         * media link. Fixed, never set from the blueprint.
         */
        "mediaPath" => function () {
            return "/" . Restaurant::MEDIA_PATH . "/";
        },
    ],
];
