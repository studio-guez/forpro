<?php

use Kirby\Cms\Page;
use Kirby\Cms\Response;
use MediumSans\Menu;
use MediumSans\Restaurant;
use Mpdf\Mpdf;
use Nuzkito\ChromePdf\ChromePdf;
use Spatie\Browsershot\Browsershot;

return [
    "routes" => function () {
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
                "pattern" => "restaurant/menu/generate",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    kirby()->impersonate("kirby");

                    $data = Menu::get();

                    $menu_page = Page::factory([
                        "slug" => "menu",
                        "template" => "menu-pdf",
                        "model" => "menu-pdf",
                        "content" => $data,
                    ]);

                    $html = $menu_page->render($data);
                    $pdfContent = Browsershot::html($html)
                        ->format("A4")
                        ->showBackground()
                        ->pdf();

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
        ];
    },
];
