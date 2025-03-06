<?php

use Eclypsys\Menu\Origin;

return [
    "pattern" => "menu/origin/(:any)/edit",
    "load" => function (string $id) {
        $origin = Origin::find($id);

        return [
            "component" => "k-form-dialog",
            "props" => [
                "fields" => require __DIR__ . "/fields.php",
                "value" => $origin,
            ],
        ];
    },
    "submit" => function (string $id) {
        return Origin::update($id, get());
    },
];
