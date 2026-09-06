<?php

use Eclypsys\Restaurant;
use Kirby\Toolkit\I18n;

/**
 * `restaurantfiles` field type.
 *
 * The `files` field of the former site blueprint, backed by the plugin media
 * library (data/restaurant-media) instead of a Kirby model. It stores a plain
 * filename and hands the panel the very same picker payload a real file field
 * does, so `k-restaurantfiles-field` can simply extend `k-files-field`.
 *
 * The picker/upload endpoints are registered in routes/index.php under
 * `restaurant/fields/<name>`, mirroring the `<model>/fields/<name>` routes
 * Kirby builds for its own fields.
 */
return [
    "props" => [
        /**
         * Unset inherited props, like the `files` field does
         */
        "after" => null,
        "before" => null,
        "autofocus" => null,
        "icon" => null,
        "placeholder" => null,

        "empty" => function ($empty = null) {
            return I18n::translate($empty, $empty);
        },
        "image" => function ($image = null) {
            return $image;
        },
        "info" => function (string|null $info = null) {
            return $info;
        },
        "layout" => function (string $layout = "list") {
            return $layout;
        },
        "link" => function (bool $link = true) {
            return $link;
        },
        "max" => function (int|null $max = 1) {
            return $max;
        },
        "min" => function (int|null $min = null) {
            return $min;
        },
        "multiple" => function (bool $multiple = false) {
            return $multiple;
        },
        "search" => function (bool $search = true) {
            return $search;
        },
        "size" => function (string|null $size = null) {
            return $size;
        },
        "text" => function (string|null $text = null) {
            return $text;
        },
        /**
         * `false` hides the upload button, a string or array sets the accepted
         * mime types — same contract as the `files` field
         */
        "uploads" => function ($uploads = true) {
            if ($uploads === false) {
                return false;
            }

            if (is_string($uploads) === true) {
                return ["accept" => $uploads];
            }

            return is_array($uploads) === true ? $uploads : [];
        },
        "value" => function ($value = null) {
            return $value;
        },
    ],
    "computed" => [
        "value" => function () {
            return Restaurant::toPickerValue($this->value);
        },
    ],
    "methods" => [
        "emptyValue" => function () {
            return "";
        },
    ],
    "save" => function ($value = null) {
        return Restaurant::toStoredMedia($value);
    },
];
