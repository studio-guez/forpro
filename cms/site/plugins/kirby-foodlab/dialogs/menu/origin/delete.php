<?php

use MediumSans\Menu\Origin;

return [
    "pattern" => "menu/origin/(:any)/delete",
    "load" => function () {
        return [
            "component" => "k-remove-dialog",
            "props" => [
                "text" => "Êtes-vous sûr de vouloir supprimer cet élément ?",
            ],
        ];
    },
    "submit" => function (string $id) {
        return Origin::delete($id);
    },
];
