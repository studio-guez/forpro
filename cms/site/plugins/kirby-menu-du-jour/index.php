<?php

load([
    "Eclypsys\MenuDuJour\BaseClass"   => __DIR__ . "/classes/BaseClass.php",
    "Eclypsys\MenuDuJour\MenuDuJour"  => __DIR__ . "/classes/MenuDuJour.php",
    "Eclypsys\MenuDuJour\FoddLab"        => __DIR__ . "/classes/FoddLab.php",
    "Eclypsys\MenuDuJour\SliderImages"   => __DIR__ . "/classes/SliderImages.php",
]);

$pluginPermissionNameForBlueprint = 'mediumsans.kirby-menu-du-jour';

Kirby::plugin("mediumsans/kirby-menu-du-jour", [
    "areas" => [
        "menu-du-jour" => function () {
            return [
                "label" => "Menu du jour",
                "icon"  => "calendar",
                "menu"  => true,
                "link"  => "kirby-menu-du-jour",
                "views" => [
                    require __DIR__ . "/views/menu-du-jour.php",
                ],
                "dialogs" => [
                    require __DIR__ . "/dialogs/menu-du-jour/create.php",
                    require __DIR__ . "/dialogs/menu-du-jour/edit.php",
                    require __DIR__ . "/dialogs/menu-du-jour/delete.php",
                    require __DIR__ . "/dialogs/menu-du-jour/duplicate.php",
                    // foddLab
                    require __DIR__ . "/dialogs/fodd-lab/create.php",
                    require __DIR__ . "/dialogs/fodd-lab/edit.php",
                    require __DIR__ . "/dialogs/fodd-lab/delete.php",
                    require __DIR__ . "/dialogs/fodd-lab/duplicate.php",
                ],
            ];
        },
    ],
    "routes" => require __DIR__ . "/routes/index.php",
    "api" => [
        "routes" => function ($kirby) {
            return [
                [
                    "pattern" => "menu-du-jour/foodcourt-texte",
                    "method"  => "POST",
                    "action"  => function () {
                        return \Eclypsys\MenuDuJour\FoddLab::setTexte(get("texte") ?? "");
                    }
                ],
                [
                    "pattern" => "menu-du-jour/slider-images",
                    "method"  => "POST",
                    "action"  => function () {
                        return \Eclypsys\MenuDuJour\SliderImages::upload(
                            get("filename") ?? "",
                            get("data") ?? "",
                            get("type") ?? ""
                        );
                    }
                ],
                [
                    "pattern" => "menu-du-jour/slider-images/(:any)",
                    "method"  => "DELETE",
                    "action"  => function (string $filename) {
                        return \Eclypsys\MenuDuJour\SliderImages::delete($filename);
                    }
                ]
            ];
        }
    ],
    "hooks" => [
        'panel.route:after' => function ($route, $path, $method) use ($pluginPermissionNameForBlueprint) {
            if ($path === null) return;
            if (!str_starts_with($path, 'kirby-menu-du-jour')) return;

            if (!kirby()->user()) return;

            $currentUserRolePermissions = kirby()->user()->role()->permissions()->toArray();
            if (!array_key_exists($pluginPermissionNameForBlueprint, $currentUserRolePermissions)) return;

            $userRoleCanAccessToThisPlugin = kirby()->user()->role()->permissions()->for($pluginPermissionNameForBlueprint, 'access');

            if (!$userRoleCanAccessToThisPlugin) go("panel/kirby-menu-du-jour");
        }
    ],
]);
