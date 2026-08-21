<?php

use Kirby\Cms\Page;
use Kirby\Cms\Response;
use Kirby\Exception\PermissionException;
use Eclypsys\Menu;
use Eclypsys\MenuSpecial;
use Eclypsys\Restaurant;
use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Utils;
use Eclypsys\MenuSpecial\DishSpecial;
use Spatie\Browsershot\Browsershot;

return [
    "routes" => function ($kirby) {
        return [
            /* Menu */
            [
                "pattern" => "restaurant/menu/create",
                "method" => "POST",
                "action" => function () {
                    return Menu::create(get());
                },
            ],
            [
                "pattern" => "restaurant/menu/generate/with-assets",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = true;

                    $data = Menu::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu",
                        "template" => "menu-pdf",
                        "model" => "menu-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->margins(0, 0, 0, 0)
                        ->setOption(
                            "addStyleTag",
                            json_encode([
                                "content" => "body { margin: 0; padding: 0; }",
                            ])
                        )
                        ->scale(1.5)
                        ->showBackground()
                        ->hideFooter()
                        ->noSandbox()
                        ->fullPage()
                        ->pdf();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                        'inline; filename="menu.pdf"',
                    ]);
                },
            ],
            [
                "pattern" => "restaurant/menu/generate/with-assets/publish",
                "method" => "POST",
                "action" => function () {
                    $renderWithAssets = true;

                    $user = kirby()->user();

                    if (
                        $user === null ||
                        $user
                            ->role()
                            ->permissions()
                            ->for("eclypsys.foodlab", "access") !== true
                    ) {
                        throw new PermissionException(
                            message: "You are not allowed to publish the menu PDF"
                        );
                    }

                    $data = Menu::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu",
                        "template" => "menu-pdf",
                        "model" => "menu-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->margins(0, 0, 0, 0)
                        ->setOption(
                            "addStyleTag",
                            json_encode([
                                "content" => "body { margin: 0; padding: 0; }",
                            ])
                        )
                        ->scale(1.5)
                        ->showBackground()
                        ->hideFooter()
                        ->noSandbox()
                        ->fullPage()
                        ->pdf();

                    $timestamp = date("Y-m-d_H-i-s");
                    $filename = "menu_{$timestamp}.pdf";

                    try {
                        $url = Restaurant::publishMenuPdf(
                            $filename,
                            $pdfContent
                        );
                    } catch (Exception $e) {
                        return \Kirby\Http\Response::json(
                            json_encode([
                                "status" => "error",
                                "message" =>
                                    "Error updating PDF: " . $e->getMessage(),
                            ]),
                            500
                        );
                    }

                    return \Kirby\Http\Response::json(
                        json_encode([
                            "status" => "ok",
                            "filename" => $filename,
                            "url" => $url,
                        ])
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/generate/without-assets",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = false;

                    $data = Menu::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu",
                        "template" => "menu-pdf",
                        "model" => "menu-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->margins(0, 0, 0, 0)
                        ->setOption(
                            "addStyleTag",
                            json_encode([
                                "content" => "body { margin: 0; padding: 0; }",
                            ])
                        )
                        ->noSandbox()
                        ->hideFooter()
                        ->hideBackground()
                        ->fullPage()
                        ->pdf();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                        'inline; filename="menu.pdf"',
                    ]);
                },
            ],
            [
                "pattern" => "restaurant/update",
                "method" => "POST",
                "action" => function () {
                    Utils::requireAccess();

                    Restaurant::save(get());

                    return ["status" => "ok"];
                },
            ],
            [
                "pattern" => "restaurant/media/upload",
                "method" => "POST",
                "action" => function () {
                    Utils::requireAccess();

                    return Restaurant::upload(
                        get("filename") ?? "",
                        get("data") ?? "",
                        get("mime") ?? ""
                    );
                },
            ],
            [
                "pattern" => "restaurant/media/(:any)",
                "method" => "GET",
                "auth" => false,
                "action" => function (string $filename) {
                    return Restaurant::serve($filename);
                },
            ],
            [
                "pattern" => "restaurant",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    return \Kirby\Http\Response::json(
                        json_encode(Restaurant::toApi())
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/(:any)/reorder",
                "method" => "POST",
                "action" => function ($category) {
                    return Menu::reorderData($category, get());
                },
            ],
            [
                "pattern" => "restaurant/menu/(:any)/up",
                "method" => "POST",
                "action" => function ($category) {
                    return Metadata::updown($category, "up");
                },
            ],
            [
                "pattern" => "restaurant/menu/(:any)/down",
                "method" => "POST",
                "action" => function ($category) {
                    return Metadata::updown($category, "down");
                },
            ],
            [
                "pattern" => "restaurant/menu/page-title-1",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "title1",
                        get("value")
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/page-title-2",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "title2",
                        get("value")
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/page-title-3",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "title3",
                        get("value")
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/page-title-4",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "title4",
                        get("value")
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/metadata/name",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        get("category"),
                        "name",
                        get("value") ?? ""
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/metadata/tva",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "tva",
                        get("value") ?? ""
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/metadata/url",
                "method" => "POST",
                "action" => function () {
                    return Metadata::addOrUpdate(
                        "page",
                        "url",
                        get("value") ?? ""
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/metadata/(:any)/order",
                "method" => "POST",
                "action" => function ($page) {
                    $order = get("order");

                    if (is_array($order)) {
                        return Metadata::addOrUpdate("page", $page, $order);
                    }

                    return false;
                },
            ],
            /* Menu Special */
            [
                "pattern" => "restaurant/menu/special/create",
                "method" => "POST",
                "action" => function () {
                    return MenuSpecial::create(get());
                },
            ],
            [
                "pattern" => "restaurant/menu/special/metadata/(:any)",
                "method" => "POST",
                "action" => function (string $pageId) {
                    return DishSpecial::setTitleOrSubtitle(
                        $pageId,
                        get("title") ?? "",
                        get("subtitle") ?? ""
                    );
                },
            ],
            [
                "pattern" => "restaurant/menu/special/generate/with-assets",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = true;

                    $data = MenuSpecial::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu-special",
                        "template" => "menu-special-pdf",
                        "model" => "menu-special-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->margins(0, 0, 0, 0)
                        ->setOption(
                            "addStyleTag",
                            json_encode([
                                "content" => "body { margin: 0; padding: 0; }",
                            ])
                        )
                        ->noSandbox()
                        ->showBackground()
                        ->hideFooter()
                        ->fullPage()
                        ->pdf();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                        'attachment; filename="menu.pdf"',
                    ]);
                },
            ],
            [
                "pattern" => "restaurant/menu/special/html",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = true;

                    $data = MenuSpecial::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu-special",
                        "template" => "menu-special-preview-pdf",
                        "model" => "menu-special-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);

                    return \Kirby\Http\Response::json(json_encode($html));
                },
            ],
            [
                "pattern" => "restaurant/menu/special/generate/without-assets",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = false;

                    $data = MenuSpecial::get($renderWithAssets);

                    $menu_page = Page::factory([
                        "slug" => "menu-special",
                        "template" => "menu-special-pdf",
                        "model" => "menu-special-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->margins(0, 0, 0, 0)
                        ->setOption(
                            "addStyleTag",
                            json_encode([
                                "content" => "body { margin: 0; padding: 0; }",
                            ])
                        )
                        ->noSandbox()
                        ->hideFooter()
                        ->hideBackground()
                        ->fullPage()
                        ->pdf();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                        'attachment; filename="menu.pdf"',
                    ]);
                },
            ],
        ];
    },
];
