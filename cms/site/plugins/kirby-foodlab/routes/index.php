<?php

use Kirby\Cms\Page;
use Kirby\Cms\Response;
use Kirby\Exception\PermissionException;
use Eclypsys\Menu;
use Eclypsys\MenuSpecial;
use Eclypsys\Menu\Metadata;
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

                    $site = kirby()->site();
                    $source = $site->mediaRoot() . "/" . $filename;

                    if (F::write($source, $pdfContent) === false) {
                        return \Kirby\Http\Response::json(
                            json_encode([
                                "status" => "error",
                                "message" =>
                                    "Failed to write PDF file to disk.",
                            ]),
                            500
                        );
                    }

                    // The foodlab role has no `site` access by design, so the
                    // write below is elevated. Scoped so it is always reset.
                    return kirby()->impersonate("kirby", function () use (
                        $site,
                        $filename,
                        $source
                    ) {
                        try {
                            $file = $site->createFile([
                                "filename" => $filename,
                                "source" => $source,
                            ]);

                            if ($file === null) {
                                return \Kirby\Http\Response::json(
                                    json_encode([
                                        "status" => "error",
                                        "message" =>
                                            "Failed to create file in Kirby.",
                                    ]),
                                    500
                                );
                            }

                            $btnLab = $site->btnLab()->toObject();
                            $btnFooter1 = $site->btnFooter1()->toObject();

                            $site->update([
                                "btnLab" => [
                                    "link" => $file->uuid(),
                                    "linkText" => $btnLab->linkText()->value(),
                                    "target" => true,
                                ],
                                "btnFooter1" => [
                                    "link" => $file->uuid(),
                                    "linkText" => $btnFooter1
                                        ->linkText()
                                        ->value(),
                                    "target" => true,
                                ],
                            ]);
                        } catch (Exception $e) {
                            F::remove($source);

                            return \Kirby\Http\Response::json(
                                json_encode([
                                    "status" => "error",
                                    "message" =>
                                        "Error updating PDF: " .
                                        $e->getMessage(),
                                ]),
                                500
                            );
                        }

                        return \Kirby\Http\Response::json(
                            json_encode([
                                "status" => "ok",
                                "filename" => $filename,
                                "url" => $file->url(),
                            ])
                        );
                    });
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
                    return Restaurant::create(get());
                },
            ],
            [
                "pattern" => "restaurant",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    // Public route: no authenticated user, so `$this->site()`
                    // (Api::site() -> Find::site()) would fail the panel access
                    // check since Kirby 5.4. Use the plain site object instead.
                    $site = kirby()->site();

                    $json = [];

                    $json["banner_info"] = $site->banner_info()->value();

                    $json["menu"] = [
                        "baseline" => $site
                            ->headline()
                            ->toHtml()
                            ->value(),
                    ];

                    $menuElements = $site->menu()->toStructure();
                    foreach ($menuElements as $element) {
                        $json["menu"]["content"][] = [
                            "text" => $element->text()->value(),
                            "link" => $element->link()->toUrl(),
                        ];
                    }

                    $btnHero1 = $site->btnHero1()->toObject();

                    $json["hero"] = [
                        "btn1" => [
                            "link" => $btnHero1->link()->toUrl(),
                            "text" => $btnHero1->linkText()->value(),
                            "target" => $btnHero1->target()->toBool(),
                        ],
                        "pictureURL1" => $site->picHero1()->toFile()
                            ? $site->picHero1()->toFile()->url()
                            : "",
                        "pictureURL2" => $site->picHero2()->toFile()
                            ? $site->picHero2()->toFile()->url()
                            : "",
                        "pictureURL3" => $site->picHero3()->toFile()
                            ? $site->picHero3()->toFile()->url()
                            : "",
                        "text" => $site->textHero1()->kt()->value(),
                    ];

                    $btnFood = $site->btnFood()->toObject();

                    $json["food"] = [
                        "title" => $site->titleFood()->value(),
                        "text" => $site->textFood()->kt()->value(),
                        "pictureURL" => $site->fileFood1()->toFile()
                            ? $site->fileFood1()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnFood->link()->toUrl(),
                            "text" => $btnFood->linkText()->value(),
                            "target" => $btnFood->target()->toBool(),
                        ],
                    ];

                    $btnLab = $site->btnLab()->toObject();

                    $json["lab"] = [
                        "title" => $site->titleLab()->value(),
                        "text" => $site->textLab()->kt()->value(),
                        "pictureURL" => $site->fileLab1()->toFile()
                            ? $site->fileLab1()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnLab->link()->toUrl(),
                            "text" => $btnLab->linkText()->value(),
                            "target" => $btnLab->target()->toBool(),
                        ],
                    ];

                    $json["highlight"] = [
                        "pictureURL" => $site->picture1()->toFile()
                            ? $site->picture1()->toFile()->url()
                            : "",
                    ];

                    $btnFormation = $site->btnFormation()->toObject();

                    $json["formation"] = [
                        "title" => $site->titleFormation()->value(),
                        "text" => $site->textFormation()->kt()->value(),
                        "pictureURL" => $site->fileFormation()->toFile()
                            ? $site->fileFormation()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnFormation->link()->toUrl(),
                            "text" => $btnFormation->linkText()->value(),
                            "target" => $btnFormation->target()->toBool(),
                        ],
                    ];

                    $json["univers"] = [
                        "title" => $site->titleUnivers()->value(),
                        "subtitle" => $site->subtitleUnivers()->value(),
                        "blogTitle1" => $site
                            ->blogUniversTitle1()
                            ->value(),
                        "blogPictureUrl1" => $site
                            ->blogUniversFil1()
                            ->toFile()
                            ? $site->blogUniversFil1()->toFile()->url()
                            : "",
                        "blogText1" => $site
                            ->blogUniversText1()
                            ->kt()
                            ->value(),
                        "blogTitle2" => $site
                            ->blogUniversTitle2()
                            ->value(),
                        "blogPictureUrl2" => $site
                            ->blogUniversFile2()
                            ->toFile()
                            ? $site->blogUniversFile2()->toFile()->url()
                            : "",
                        "blogText2" => $site
                            ->blogUniversText2()
                            ->kt()
                            ->value(),
                    ];

                    $json["values"] = [
                        "title" => $site->titleValues()->value(),
                        "text" => $site->textValues()->value(),
                    ];

                    $values = $site->lstValues()->toStructure();
                    foreach ($values as $value) {
                        $json["values"]["list"][] = [
                            "title" => $value->title()->value(),
                            "icon" => $value->icon()->toFile()
                                ? $value->icon()->toFile()->url()
                                : "",
                        ];
                    }

                    $btnFooter1 = $site->btnFooter1()->toObject();
                    $btnFooter2 = $site->btnFooter2()->toObject();

                    $json["footer"] = [
                        "Headline" => $site->footerHeadline()->value(),
                        "text1" => $site->textFooter1()->kt()->value(),
                        "text2" => $site->textFooter2()->kt()->value(),
                        "text3" => $site->textFooter3()->kt()->value(),
                        "btn1" => [
                            "link" => $btnFooter1->link()->toUrl(),
                            "text" => $btnFooter1->linkText()->value(),
                            "target" => $btnFooter1->target()->toBool(),
                        ],
                        "btn2" => [
                            "link" => $btnFooter2->link()->toUrl(),
                            "text" => $btnFooter2->linkText()->value(),
                            "target" => $btnFooter2->target()->toBool(),
                        ],
                    ];

                    return \Kirby\Http\Response::json(json_encode($json));
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
