<?php

@include_once __DIR__ . "/vendor/autoload.php";

load([
    "Eclypsys\BaseClass"                  => __DIR__ . "/classes/BaseClass.php",
    "Eclypsys\Menu"                       => __DIR__ . "/classes/Menu.php",
    "Eclypsys\Menu\Metadata"              => __DIR__ . "/classes/Metadata.php",
    "Eclypsys\Menu\MainCourse"            => __DIR__ . "/classes/MainCourse.php",
    "Eclypsys\Menu\Starter"               => __DIR__ . "/classes/Starter.php",
    "Eclypsys\Menu\Dessert"               => __DIR__ . "/classes/Dessert.php",
    "Eclypsys\Menu\Wine"                  => __DIR__ . "/classes/Wine.php",
    "Eclypsys\Menu\RedWine"               => __DIR__ . "/classes/RedWine.php",
    "Eclypsys\Menu\WhiteWine"             => __DIR__ . "/classes/WhiteWine.php",
    "Eclypsys\Menu\BubbleWine"            => __DIR__ . "/classes/BubbleWine.php",
    "Eclypsys\Menu\SoftDrink"             => __DIR__ . "/classes/SoftDrink.php",
    "Eclypsys\Menu\Beer"                  => __DIR__ . "/classes/Beer.php",
    "Eclypsys\Menu\Cocktail"              => __DIR__ . "/classes/Cocktail.php",
    "Eclypsys\Menu\HotDrink"              => __DIR__ . "/classes/HotDrink.php",
    "Eclypsys\Menu\Origin"                => __DIR__ . "/classes/Origin.php",
    "Eclypsys\MenuSpecial"                => __DIR__ . "/classes/MenuSpecial.php",
    "Eclypsys\MenuSpecial\DishSpecial"    => __DIR__ . "/classes/DishSpecial.php",
    "Eclypsys\MenuSpecial\WineSpecial"    => __DIR__ . "/classes/WineSpecial.php",
    "Eclypsys\Menu\Utils"                 => __DIR__ . "/classes/Utils.php"
]);

$pluginPermissionNameForBlueprint = 'eclypsys.kirby-foodlab';

Kirby::plugin("eclypsys/foodlab", [
    'hooks' => [
        'page.render:before' => function () {
            var_dump(kirby()->roots);
            die();
            $dataFolder = __DIR__ . '/data';

            if (!is_dir($dataFolder)) {
                Dir::make($dataFolder);

                $jsonFiles = [
                    'beer.json',
                    'bubblewine.json',
                    'cocktail.json',
                    'dessert.json',
                    'hotdrink.json',
                    'maincourse.json',
                    'menu.json',
                    'menu-special.json',
                    'metadata.json',
                    'origin.json',
                    'redwine.json',
                    'softdrink.json',
                    'starter.json',
                    'whitewine.json'
                ];

                foreach ($jsonFiles as $filename) {
                    F::write(
                        $dataFolder . '/' . $filename,
                        '[]'
                    );
                }
            }
        }
    ],
    "areas" => [
        "menu" => function ($kirby) {
            return [
                "label" => "Menu",
                "menu" => true,
                "icon" => "file-document",
                "link" => "foodlab/restaurant/menu",
                "view" => "k-menu-view",
                "views" => [
                    require __DIR__ . "/views/menu.php",
                    require __DIR__ . '/views/deniedAccess.php',
                ],
                "dialogs" => [
                    // Beer
                    require __DIR__ . "/dialogs/menu/beer/fields.php",
                    require __DIR__ . "/dialogs/menu/beer/create.php",
                    require __DIR__ . "/dialogs/menu/beer/delete.php",
                    require __DIR__ . "/dialogs/menu/beer/edit.php",
                    require __DIR__ . "/dialogs/menu/beer/hide.php",
                    require __DIR__ . "/dialogs/menu/beer/title.php",
                    // Bubble Wine
                    require __DIR__ . "/dialogs/menu/bubblewine/fields.php",
                    require __DIR__ . "/dialogs/menu/bubblewine/create.php",
                    require __DIR__ . "/dialogs/menu/bubblewine/delete.php",
                    require __DIR__ . "/dialogs/menu/bubblewine/edit.php",
                    require __DIR__ . "/dialogs/menu/bubblewine/hide.php",
                    require __DIR__ . "/dialogs/menu/bubblewine/title.php",
                    // Cocktail
                    require __DIR__ . "/dialogs/menu/cocktail/fields.php",
                    require __DIR__ . "/dialogs/menu/cocktail/create.php",
                    require __DIR__ . "/dialogs/menu/cocktail/delete.php",
                    require __DIR__ . "/dialogs/menu/cocktail/edit.php",
                    require __DIR__ . "/dialogs/menu/cocktail/hide.php",
                    require __DIR__ . "/dialogs/menu/cocktail/title.php",
                    // Dessert
                    require __DIR__ . "/dialogs/menu/dessert/fields.php",
                    require __DIR__ . "/dialogs/menu/dessert/create.php",
                    require __DIR__ . "/dialogs/menu/dessert/delete.php",
                    require __DIR__ . "/dialogs/menu/dessert/edit.php",
                    require __DIR__ . "/dialogs/menu/dessert/hide.php",
                    require __DIR__ . "/dialogs/menu/dessert/title.php",
                    // Hot Drink
                    require __DIR__ . "/dialogs/menu/hotdrink/fields.php",
                    require __DIR__ . "/dialogs/menu/hotdrink/create.php",
                    require __DIR__ . "/dialogs/menu/hotdrink/delete.php",
                    require __DIR__ . "/dialogs/menu/hotdrink/edit.php",
                    require __DIR__ . "/dialogs/menu/hotdrink/hide.php",
                    require __DIR__ . "/dialogs/menu/hotdrink/title.php",
                    // Main Course
                    require __DIR__ . "/dialogs/menu/maincourse/fields.php",
                    require __DIR__ . "/dialogs/menu/maincourse/create.php",
                    require __DIR__ . "/dialogs/menu/maincourse/delete.php",
                    require __DIR__ . "/dialogs/menu/maincourse/edit.php",
                    require __DIR__ . "/dialogs/menu/maincourse/hide.php",
                    require __DIR__ . "/dialogs/menu/maincourse/title.php",
                    // Red Wine
                    require __DIR__ . "/dialogs/menu/redwine/fields.php",
                    require __DIR__ . "/dialogs/menu/redwine/create.php",
                    require __DIR__ . "/dialogs/menu/redwine/delete.php",
                    require __DIR__ . "/dialogs/menu/redwine/edit.php",
                    require __DIR__ . "/dialogs/menu/redwine/hide.php",
                    require __DIR__ . "/dialogs/menu/redwine/title.php",
                    // Soft Drink
                    require __DIR__ . "/dialogs/menu/softdrink/fields.php",
                    require __DIR__ . "/dialogs/menu/softdrink/create.php",
                    require __DIR__ . "/dialogs/menu/softdrink/delete.php",
                    require __DIR__ . "/dialogs/menu/softdrink/edit.php",
                    require __DIR__ . "/dialogs/menu/softdrink/hide.php",
                    require __DIR__ . "/dialogs/menu/softdrink/title.php",
                    // Starter
                    require __DIR__ . "/dialogs/menu/starter/fields.php",
                    require __DIR__ . "/dialogs/menu/starter/create.php",
                    require __DIR__ . "/dialogs/menu/starter/delete.php",
                    require __DIR__ . "/dialogs/menu/starter/edit.php",
                    require __DIR__ . "/dialogs/menu/starter/hide.php",
                    require __DIR__ . "/dialogs/menu/starter/title.php",
                    // White Wine
                    require __DIR__ . "/dialogs/menu/whitewine/fields.php",
                    require __DIR__ . "/dialogs/menu/whitewine/create.php",
                    require __DIR__ . "/dialogs/menu/whitewine/delete.php",
                    require __DIR__ . "/dialogs/menu/whitewine/edit.php",
                    require __DIR__ . "/dialogs/menu/whitewine/hide.php",
                    require __DIR__ . "/dialogs/menu/whitewine/title.php",
                    // Origins
                    require __DIR__ . "/dialogs/menu/origin/fields.php",
                    require __DIR__ . "/dialogs/menu/origin/create.php",
                    require __DIR__ . "/dialogs/menu/origin/delete.php",
                    require __DIR__ . "/dialogs/menu/origin/edit.php",
                ],
            ];
        },
        "menu-special" => function ($kirby) {
            return [
                "label" => "Menu Spécial",
                "menu" => true,
                "icon" => "badge",
                "link" => "foodlab/restaurant/menu/special",
                "view" => "k-menu-special-view",
                "views" => [require __DIR__ . "/views/menu-special.php"],
                "dialogs" => [
                    // Dish
                    require __DIR__ . "/dialogs/menu-special/dish/fields.php",
                    require __DIR__ . "/dialogs/menu-special/dish/add.php",
                    require __DIR__ . "/dialogs/menu-special/dish/delete.php",
                    require __DIR__ . "/dialogs/menu-special/dish/edit.php",
                    // Wine
                    require __DIR__ . "/dialogs/menu-special/wine/fields.php",
                    require __DIR__ . "/dialogs/menu-special/wine/add.php",
                    require __DIR__ . "/dialogs/menu-special/wine/delete.php",
                    require __DIR__ . "/dialogs/menu-special/wine/edit.php",
                ]
            ];
        }
    ],
    "templates" => [
        "menu-pdf" => __DIR__ . "/templates/menu-pdf.php",
        "menu-special-pdf" => __DIR__ . "/templates/menu-special-pdf.php",
        "menu-special-preview-pdf" => __DIR__ . "/templates/menu-special-preview-pdf.php",
    ],
    "blueprints" => [
        "tabs/restaurant" => __DIR__ . "/blueprints/tabs/restaurant.yml",
        "pages/restaurant" => __DIR__ . "/blueprints/pages/restaurant.yml",
    ],
    "api" => require __DIR__ . "/routes/index.php",

    'hooks' => [
        'panel.route:after' => function ($route, $path, $method) use ($pluginPermissionNameForBlueprint) {
            \Eclypsys\Menu\Utils::checkRoleAccess($route, $path, $method, $pluginPermissionNameForBlueprint);
        }
    ],
]);
