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
                        "btn1" => $this->site()->btnHero1()->value,
                        "btn2" => $this->site()->btnHero2()->value(),
                        "pictureURL1" => $this->site()->picHero1()->toFile() ? $this->site()->picHero1()->toFile()->url() : '',
                        "pictureURL2" => $this->site()->picHero2()->toFile() ? $this->site()->picHero2()->toFile()->url() : '',
                        "pictureURL3" => $this->site()->picHero3()->toFile() ? $this->site()->picHero3()->toFile()->url() : '',
                        "text" => $this->site()->textHero1()->kt()->value(),
                    ];

                    $json["food"] = [

                        "title" => $this->site()->titleFood()->value(),
                        "text" => $this->site()->textFood()->kt()->value(),
                        "pictureURL" => $this->site()->fileFood1()->toFile() ? $this->site()->fileFood1()->toFile()->url() : '',
                        "btn" => $this->site()->btnFood()->value()
                    ];

                    $json["lab"] = [
                        "title" => $this->site()->titleLab()->value(),
                        "text" => $this->site()->textLab()->kt()->value(),
                        "pictureURL" => $this->site()->fileLab1()->toFile() ? $this->site()->fileLab1()->toFile()->url() : '',
                        "btn" => $this->site()->btnLab()->value()
                    ];

                    $json["highlight"] = [
                        "pictureURL" => $this->site()->picture1()->toFile() ? $this->site()->picture1()->toFile()->url() : '',
                    ];

                    $json["formation"] = [
                        "title" => $this->site()->titleFormation()->value(),
                        "text" => $this->site()->textFormation()->kt()->value(),
                        "pictureURL" => $this->site()
                            ->fileFormation()
                            ->toFile() ? $this->site()->fileFormation()->toFile()->url() : '',
                        "btn" => $this->site()->btnFormation()->value()
                    ];

                    $json["univers"] = [
                        "title" => $this->site()->titleUnivers()->value(),
                        "subtitle" => $this->site()->subtitleUnivers()->value(),
                        "blogTitle1" => $this->site()
                            ->blogUniversTitle1()
                            ->value(),
                        "blogPictureUrl1" => $this->site()->blogUniversFil1()->value(),
                        "blogText1" => $this->site()
                            ->blogUniversText1()
                            ->kt()->value(),
                        "blogTitle2" => $this->site()
                            ->blogUniversTitle2()
                            ->value(),
                        "blogPictureUrl2" => $this->site()
                            ->blogUniversFile2()
                            ->value(),
                        "blogText2" => $this->site()
                            ->blogUniversText2()
                            ->kt()->value()
                    ];

                    $json["values"] = [
                        "title" => $this->site()->titleValues()->value(),
                        "text" => $this->site()->textValues()->value(),
                        "list" => $this->site()
                            ->lstValues()
                            ->toStructure()
                            ->toArray()
                    ];

                    $json["footer"] = [
                        "Headline" => $this->site()->footerHeadline()->value(),
                        "text1" => $this->site()->textFooter1()->kt()->value(),
                        "text2" => $this->site()->textFooter2()->kt()->value(),
                        "text3" => $this->site()->textFooter3()->kt()->value(),
                        "btn1" => $this->site()->btnFooter1()->value(),
                        "btn2" => $this->site()->btnFooter2()->value()
                    ];

                    return \Kirby\Http\Response::json(json_encode($json));
                },
            ],
        ];
    },
];
