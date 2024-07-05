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
            [
                "pattern" => "restaurant",
                "method" => "GET",
                "auth" => false,
                "action" => function () {
                    $json = [];

                    $json["body"] = [
                        "btnHero1" => $site->btnHero1()->value(),
                        "btnHero2" => $site->btnHero2()->value(),
                        "picHero1" => $site->picHero1()->toFile()->url(),
                        "picHero2" => $site->picHero2()->toFile()->url(),
                        "picHero3" => $site->picHero3()->toFile()->url(),
                        "textHero1" => $site->textHero1()->value(),
                        "titleFood" => $site->titleFood()->value(),
                        "textFood" => $site->textFood()->value(),
                        "fileFood1" => $site->fileFood1()->toFile()->url(),
                        "btnFood" => $site->btnFood()->value(),
                        "titleLab" => $site->titleLab()->value(),
                        "textLab" => $site->textLab()->value(),
                        "fileLab1" => $site->fileLab1()->toFile()->url(),
                        "btnLab" => $site->btnLab()->value(),
                        "picture1" => $site->picture1()->toFile()->url(),
                        "titleFormation" => $site->titleFormation()->value(),
                        "textFormation" => $site->textFormation()->value(),
                        "fileFormation" => $site
                            ->fileFormation()
                            ->toFile()
                            ->url(),
                        "btnFormation" => $site->btnFormation()->value(),
                        "titleUnivers" => $site->titleUnivers()->value(),
                        "subtitleUnivers" => $site->subtitleUnivers()->value(),
                        "blogUniversTitle1" => $site
                            ->blogUniversTitle1()
                            ->value(),
                        "blogUniversFil1" => $site->blogUniversFil1()->value(),
                        "blogUniversText1" => $site
                            ->blogUniversText1()
                            ->value(),
                        "blogUniversTitle2" => $site
                            ->blogUniversTitle2()
                            ->value(),
                        "blogUniversFile2" => $site
                            ->blogUniversFile2()
                            ->value(),
                        "blogUniversText2" => $site
                            ->blogUniversText2()
                            ->value(),
                        "titleValues" => $site->titleValues()->value(),
                        "textValues" => $site->textValues()->value(),
                        "lstValues" => $site
                            ->lstValues()
                            ->toStructure()
                            ->toArray(),
                        "footerHeadline" => $site->footerHeadline()->value(),
                        "textFooter1" => $site->textFooter1()->value(),
                        "textFooter2" => $site->textFooter2()->value(),
                        "textFooter3" => $site->textFooter3()->value(),
                        "btnFooter1" => $site->btnFooter1()->value(),
                        "btnFooter2" => $site->btnFooter2()->value(),
                    ];

                    return json_encode($json);
                },
            ],
        ];
    },
];
