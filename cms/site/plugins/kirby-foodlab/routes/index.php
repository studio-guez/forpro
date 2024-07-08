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

                    $json["menu"] = [
                        "baseline" => $this->site()
                            ->headline()
                            ->toHtml()
                            ->value(),
                    ];

                    $btnHero1 = $this->site()->btnHero1()->toObject();
                    $btnHero2 = $this->site()->btnHero2()->toObject();

                    $json["hero"] = [
                        "btn1" => [
                            "text" => $btnHero1->link()->toUrl(),
                            "url" => $btnHero1->linkText()->value(),
                        ],
                        "btn2" => [
                            "text" => $btnHero2->link()->toUrl(),
                            "url" => $btnHero2->linkText()->value(),
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
                            "text" => $btnFood->link()->toUrl(),
                            "url" => $btnFood->linkText()->value(),
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
                            "text" => $btnLab->link()->toUrl(),
                            "url" => $btnLab->linkText()->value(),
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
                            "text" => $btnFormation->link()->toUrl(),
                            "url" => $btnFormation->linkText()->value(),
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
                        "list" => $this->site()
                            ->lstValues()
                            ->toStructure()
                            ->toArray(),
                    ];

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
                        ],
                        "btn2" => [
                            "link" => $btnFooter2->link()->toUrl(),
                            "text" => $btnFooter2->linkText()->value(),
                        ],
                    ];

                    return \Kirby\Http\Response::json(json_encode($json));
                },
            ],
        ];
    },
];
