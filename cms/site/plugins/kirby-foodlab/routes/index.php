<?php

use Kirby\Cms\Page;
use Kirby\Cms\Response;
use MediumSans\Menu;
use MediumSans\MenuSpecial;
use MediumSans\Menu\Metadata;
use MediumSans\MenuSpecial\DishSpecial;
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

                    kirby()->impersonate("kirby");

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

                    $pdfHtml = Browsershot::html($html)
                        ->format("A4")
                        ->bodyHtml();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                            'attachment; filename="menu.pdf"',
                    ]);
                },
            ],
            [
                "pattern" => "restaurant/menu/generate/with-assets/publish",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = true;

                    kirby()->impersonate("kirby");

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

                    $source = $this->site()->mediaRoot() . "/" . $filename;
                    $result = F::write($source, $pdfContent);

                    if ($result) {
                        try {
                            $file = $this->site()->createFile([
                                "filename" => $filename,
                                "source" => $source,
                            ]);

                            $btnHero2 = $this->site()->btnHero2()->toObject();
                            $btnLab = $this->site()->btnLab()->toObject();
                            $btnFooter1 = $this->site()
                                ->btnFooter1()
                                ->toObject();

                            if ($file) {
                                $this->site()->update([
                                    "btnHero2" => [
                                        "link" => $file->uuid(),
                                        "linkText" => $btnHero2
                                            ->linkText()
                                            ->value(),
                                        "target" => true,
                                    ],
                                    "btnLab" => [
                                        "link" => $file->uuid(),
                                        "linkText" => $btnLab
                                            ->linkText()
                                            ->value(),
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
                                echo "PDF updated successfully: $filename";
                            } else {
                                echo "Failed to create file in Kirby.";
                            }
                        } catch (Exception $e) {
                            echo "Error updating PDF: " . $e->getMessage();
                            F::remove($source);
                        }
                    } else {
                        echo "Failed to write PDF file to disk.";
                    }

                    $pdfHtml = Browsershot::html($html)
                        ->format("A4")
                        ->bodyHtml();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                            'attachment; filename="menu.pdf"',
                    ]);
                },
            ],
            [
                "pattern" => "restaurant/menu/generate/without-assets",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $renderWithAssets = false;

                    kirby()->impersonate("kirby");

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

                    $pdfHtml = Browsershot::html($html)
                        ->format("A4")
                        ->bodyHtml();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                            'attachment; filename="menu.pdf"',
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
                    $json = [];

                    $json["menu"] = [
                        "baseline" => $this->site()
                            ->headline()
                            ->toHtml()
                            ->value(),
                    ];

                    $menuElements = $this->site()->menu()->toStructure();
                    foreach ($menuElements as $element) {
                        $json["menu"]["content"][] = [
                            "text" => $element->text()->value(),
                            "link" => $element->link()->toUrl(),
                        ];
                    }

                    $btnHero1 = $this->site()->btnHero1()->toObject();
                    $btnHero2 = $this->site()->btnHero2()->toObject();

                    $json["hero"] = [
                        "btn1" => [
                            "link" => $btnHero1->link()->toUrl(),
                            "text" => $btnHero1->linkText()->value(),
                            "target" => $btnHero1->target()->toBool(),
                        ],
                        "btn2" => [
                            "link" => $btnHero2->link()->toUrl(),
                            "text" => $btnHero2->linkText()->value(),
                            "target" => $btnHero2->target()->toBool(),
                        ],
                        "pictureURL1" => $this->site()->picHero1()->toFile()
                            ? $this->site()->picHero1()->toFile()->url()
                            : "",
                        "pictureURL2" => $this->site()->picHero2()->toFile()
                            ? $this->site()->picHero2()->toFile()->url()
                            : "",
                        "pictureURL3" => $this->site()->picHero3()->toFile()
                            ? $this->site()->picHero3()->toFile()->url()
                            : "",
                        "text" => $this->site()->textHero1()->kt()->value(),
                    ];

                    $btnFood = $this->site()->btnFood()->toObject();

                    $json["food"] = [
                        "title" => $this->site()->titleFood()->value(),
                        "text" => $this->site()->textFood()->kt()->value(),
                        "pictureURL" => $this->site()->fileFood1()->toFile()
                            ? $this->site()->fileFood1()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnFood->link()->toUrl(),
                            "text" => $btnFood->linkText()->value(),
                            "target" => $btnFood->target()->toBool(),
                        ],
                    ];

                    $btnLab = $this->site()->btnLab()->toObject();

                    $json["lab"] = [
                        "title" => $this->site()->titleLab()->value(),
                        "text" => $this->site()->textLab()->kt()->value(),
                        "pictureURL" => $this->site()->fileLab1()->toFile()
                            ? $this->site()->fileLab1()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnLab->link()->toUrl(),
                            "text" => $btnLab->linkText()->value(),
                            "target" => $btnLab->target()->toBool(),
                        ],
                    ];

                    $json["highlight"] = [
                        "pictureURL" => $this->site()->picture1()->toFile()
                            ? $this->site()->picture1()->toFile()->url()
                            : "",
                    ];

                    $btnFormation = $this->site()->btnFormation()->toObject();

                    $json["formation"] = [
                        "title" => $this->site()->titleFormation()->value(),
                        "text" => $this->site()->textFormation()->kt()->value(),
                        "pictureURL" => $this->site()->fileFormation()->toFile()
                            ? $this->site()->fileFormation()->toFile()->url()
                            : "",
                        "btn" => [
                            "link" => $btnFormation->link()->toUrl(),
                            "text" => $btnFormation->linkText()->value(),
                            "target" => $btnFormation->target()->toBool(),
                        ],
                    ];

                    $json["univers"] = [
                        "title" => $this->site()->titleUnivers()->value(),
                        "subtitle" => $this->site()->subtitleUnivers()->value(),
                        "blogTitle1" => $this->site()
                            ->blogUniversTitle1()
                            ->value(),
                        "blogPictureUrl1" => $this->site()
                            ->blogUniversFil1()
                            ->toFile()
                            ? $this->site()->blogUniversFil1()->toFile()->url()
                            : "",
                        "blogText1" => $this->site()
                            ->blogUniversText1()
                            ->kt()
                            ->value(),
                        "blogTitle2" => $this->site()
                            ->blogUniversTitle2()
                            ->value(),
                        "blogPictureUrl2" => $this->site()
                            ->blogUniversFile2()
                            ->toFile()
                            ? $this->site()->blogUniversFile2()->toFile()->url()
                            : "",
                        "blogText2" => $this->site()
                            ->blogUniversText2()
                            ->kt()
                            ->value(),
                    ];

                    $json["values"] = [
                        "title" => $this->site()->titleValues()->value(),
                        "text" => $this->site()->textValues()->value(),
                    ];

                    $values = $this->site()->lstValues()->toStructure();
                    foreach ($values as $value) {
                        $json["values"]["list"][] = [
                            "title" => $value->title()->value(),
                            "icon" => $value->icon()->toFile()
                                ? $value->icon()->toFile()->url()
                                : "",
                        ];
                    }

                    $btnFooter1 = $this->site()->btnFooter1()->toObject();
                    $btnFooter2 = $this->site()->btnFooter2()->toObject();

                    $json["footer"] = [
                        "Headline" => $this->site()->footerHeadline()->value(),
                        "text1" => $this->site()->textFooter1()->kt()->value(),
                        "text2" => $this->site()->textFooter2()->kt()->value(),
                        "text3" => $this->site()->textFooter3()->kt()->value(),
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

                    kirby()->impersonate("kirby");

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

                    $pdfHtml = Browsershot::html($html)
                        ->format("A4")
                        ->bodyHtml();

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

                    kirby()->impersonate("kirby");

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

                    kirby()->impersonate("kirby");

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

                    $pdfHtml = Browsershot::html($html)
                        ->format("A4")
                        ->bodyHtml();

                    return new Response($pdfContent, "application/pdf", 200, [
                        "Content-Disposition" =>
                            'attachment; filename="menu.pdf"',
                    ]);
                },
            ],
        ];
    },
];
