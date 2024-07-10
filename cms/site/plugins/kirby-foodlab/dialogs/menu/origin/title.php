<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\Origin;

return [
    "pattern" => "menu/origin/title",
    "load" => function () {
        $name = Metadata::get(Origin::name(), "name") ?? "";
        $value = ["name" => $name];

        return [
            "component" => "k-form-dialog",
            "props" => [
                "fields" => [
                    "name" => [
                        "label" => "Titre de section",
                        "type" => "text",
                    ],
                ],
                "value" => $value,
            ],
        ];
    },
    "submit" => function () {
        $name = get("name");
        return Metadata::addOrUpdate(Origin::name(), "name", $name ?? "");
    },
];
