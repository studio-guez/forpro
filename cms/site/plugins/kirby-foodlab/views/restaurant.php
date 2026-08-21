<?php

use Eclypsys\Restaurant;

return [
    "pattern" => "foodlab/restaurant/content",
    "action" => function () {
        Restaurant::requireEditPermission();

        $fields = Restaurant::fields();
        $data = Restaurant::get();
        $values = [];

        foreach ($fields as $name => $field) {
            if (in_array($field["type"], ["headline", "line"], true)) {
                continue;
            }

            $values[$name] =
                $data[$name] ??
                match ($field["type"]) {
                    "structure" => [],
                    "object" => [],
                    "toggle" => false,
                    default => "",
                };
        }

        return [
            "component" => "k-restaurant-view",
            "props" => [
                "fields" => $fields,
                "content" => $values,
            ],
        ];
    },
];
