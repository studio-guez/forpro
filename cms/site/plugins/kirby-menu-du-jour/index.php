<?php

load([
    "Eclypsys\MenuDuJour\BaseClass"   => __DIR__ . "/classes/BaseClass.php",
    "Eclypsys\MenuDuJour\MenuDuJour"  => __DIR__ . "/classes/MenuDuJour.php",
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
                ],
            ];
        },
    ],
    "routes" => require __DIR__ . "/routes/index.php",
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
