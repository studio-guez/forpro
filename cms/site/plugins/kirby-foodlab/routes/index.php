<?php

use Kirby\Cms\Page;
use Kirby\Cms\Response;
use MediumSans\Menu;
use MediumSans\Restaurant;
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

                    $json["hero"] = [
                        "btnHero1" => $this->site()->btnHero1()->value,
                        "btnHero2" => $this->site()->btnHero2()->value(),
                        "picHero1" => $this->site()->picHero1()->toFile() ? $this->site()->picHero1()->toFile()->url() : '',
                        "picHero2" => $this->site()->picHero2()->toFile() ? $this->site()->picHero2()->toFile()->url() : '',
                        "picHero3" => $this->site()->picHero3()->toFile() ? $this->site()->picHero3()->toFile()->url() : '',
                        "textHero1" => $this->site()->textHero1()->kt()->value(),
                    ];

                    $json["food"] = [

                        "titleFood" => $this->site()->titleFood()->value(),
                        "textFood" => $this->site()->textFood()->kt()->value(),
                        "fileFood1" => $this->site()->fileFood1()->toFile() ? $this->site()->fileFood1()->toFile()->url() : '',
                        "btnFood" => $this->site()->btnFood()->value()
                    ];

                    $json["lab"] = [
                        "titleLab" => $this->site()->titleLab()->value(),
                        "textLab" => $this->site()->textLab()->kt()->value(),
                        "fileLab1" => $this->site()->fileLab1()->toFile() ? $this->site()->fileLab1()->url() : '',
                        "btnLab" => $this->site()->btnLab()->value()
                    ];

                    $json["highlight"] = [
                        "picture1" => $this->site()->picture1()->toFile() ? $this->site()->picture1()->toFile()->url() : '',
                    ];

                    $json["formation"] = [
                        "titleFormation" => $this->site()->titleFormation()->value(),
                        "textFormation" => $this->site()->textFormation()->kt()->value(),
                        "fileFormation" => $this->site()
                            ->fileFormation()
                            ->toFile() ? $this->site()->fileFormation()->toFile()->url() : '',
                        "btnFormation" => $this->site()->btnFormation()->value()
                    ];

                    $json["univers"] = [
                        "titleUnivers" => $this->site()->titleUnivers()->value(),
                        "subtitleUnivers" => $this->site()->subtitleUnivers()->value(),
                        "blogUniversTitle1" => $this->site()
                            ->blogUniversTitle1()
                            ->value(),
                        "blogUniversFil1" => $this->site()->blogUniversFil1()->value(),
                        "blogUniversText1" => $this->site()
                            ->blogUniversText1()
                            ->kt()->value(),
                        "blogUniversTitle2" => $this->site()
                            ->blogUniversTitle2()
                            ->value(),
                        "blogUniversFile2" => $this->site()
                            ->blogUniversFile2()
                            ->value(),
                        "blogUniversText2" => $this->site()
                            ->blogUniversText2()
                            ->kt()->value()
                    ];

                    $json["values"] = [
                        "titleValues" => $this->site()->titleValues()->value(),
                        "textValues" => $this->site()->textValues()->value(),
                        "lstValues" => $this->site()
                            ->lstValues()
                            ->toStructure()
                            ->toArray()
                    ];

                    $json["footer"] = [
                        "footerHeadline" => $this->site()->footerHeadline()->value(),
                        "textFooter1" => $this->site()->textFooter1()->kt()->value(),
                        "textFooter2" => $this->site()->textFooter2()->kt()->value(),
                        "textFooter3" => $this->site()->textFooter3()->kt()->value(),
                        "btnFooter1" => $this->site()->btnFooter1()->value(),
                        "btnFooter2" => $this->site()->btnFooter2()->value()
                    ];

                    return \Kirby\Http\Response::json(json_encode($json));
                },
            ],
        ];
    },
];
