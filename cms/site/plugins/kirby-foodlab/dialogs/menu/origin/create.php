<?php

use Eclypsys\Menu\Origin;

return [
    "pattern" => "menu/origin/create",
    "load" => function () {
        return [
            "component" => "k-form-dialog",
            "props" => [
                "fields" => require __DIR__ . "/fields.php",
                "submitButton" => t("create"),
                "size" => "large",
            ],
        ];
    },
    "submit" => function () {
        return Origin::create(get());
    },
];
