<?php

use Eclypsys\Restaurant;

/**
 * Fields of the restaurant content form (custom panel view, see
 * views/restaurant.php). The keys are also the keys stored in
 * data/restaurant.json.
 */

$image = [
    "type" => "restaurantimage",
    "mediaBase" => url(Restaurant::MEDIA_PATH) . "/",
];

$button = [
    "type" => "object",
    "width" => "1/2",
    "fields" => [
        "link" => [
            "label" => "Lien",
            "type" => "text",
            "help" => "URL complète (https://…), ancre (#lefood) ou mailto:",
        ],
        "linkText" => [
            "label" => "Texte",
            "type" => "text",
        ],
        "target" => [
            "label" => "Ouvrir dans une nouvelle fenêtre",
            "type" => "toggle",
        ],
    ],
];

return [
    "banner_info" => [
        "label" => "Bandeau",
        "type" => "writer",
        "inline" => true,
        "marks" => ["bold", "italic", "link"],
    ],

    "menuHeadline" => [
        "label" => "Menu",
        "type" => "headline",
    ],

    "menu" => [
        "label" => "Liens",
        "type" => "structure",
        "fields" => [
            "text" => [
                "label" => "Texte",
                "type" => "text",
            ],
            "link" => [
                "label" => "Lien",
                "type" => "text",
                "help" =>
                    "Ancres disponibles: #hero, #lefood, #lelab, #equipe-formation, #foodcourt-popup-cafe, #engagements",
            ],
        ],
    ],

    "headline" => [
        "label" => "Baseline",
        "type" => "text",
    ],

    "hero1" => [
        "label" => "Héro 1",
        "type" => "headline",
    ],

    "btnHero1" => ["label" => "Bouton"] + $button,

    "picHero1" => ["label" => "Image 1", "width" => "1/2"] + $image,
    "picHero2" => ["label" => "Image 2", "width" => "1/2"] + $image,
    "picHero3" => ["label" => "Image 3", "width" => "1/2"] + $image,

    "textHero1" => [
        "label" => "Text Hero 1",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "line1" => ["type" => "line"],

    "lefood" => [
        "label" => "Le Food",
        "type" => "headline",
    ],

    "titleFood" => [
        "label" => "Title 1",
        "type" => "text",
    ],

    "textFood" => [
        "label" => "Text",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "fileFood1" => ["label" => "Image 1", "width" => "1/2"] + $image,

    "btnFood" => ["label" => "Bouton"] + $button,

    "line2" => ["type" => "line"],

    "lelab" => [
        "label" => "Le Lab",
        "type" => "headline",
    ],

    "titleLab" => [
        "label" => "Title 1",
        "type" => "text",
    ],

    "fileLab1" => ["label" => "Image 1", "width" => "1/2"] + $image,

    "textLab" => [
        "label" => "Text",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "btnLab" =>
        [
            "label" => "Bouton",
            "help" =>
                "Le lien est remplacé automatiquement par le PDF publié depuis « Menu »",
        ] + $button,

    "line3" => ["type" => "line"],

    "picture1" => ["label" => "Image 1"] + $image,

    "line4" => ["type" => "line"],

    "formation" => [
        "label" => "Equipe & Formation",
        "type" => "headline",
    ],

    "titleFormation" => [
        "label" => "Title 1",
        "type" => "text",
    ],

    "textFormation" => [
        "label" => "Text",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "fileFormation" => ["label" => "Image 1", "width" => "1/2"] + $image,

    "btnFormation" => ["label" => "Bouton"] + $button,

    "line5" => ["type" => "line"],

    "univers" => [
        "label" => "Univers du Food",
        "type" => "headline",
    ],

    "titleUnivers" => [
        "label" => "Title",
        "type" => "text",
    ],

    "subtitleUnivers" => [
        "label" => "Subtitle",
        "type" => "text",
    ],

    "blogUniversTitle1" => [
        "label" => "Title",
        "type" => "text",
        "width" => "1/2",
    ],

    "blogUniversFil1" => ["label" => "Illustration", "width" => "1/2"] + $image,

    "blogUniversText1" => [
        "label" => "Text",
        "type" => "textarea",
        "uploads" => false,
    ],

    "blogUniversTitle2" => [
        "label" => "Title",
        "type" => "text",
        "width" => "1/2",
    ],

    "blogUniversFile2" => ["label" => "Illustration", "width" => "1/2"] + $image,

    "blogUniversText2" => [
        "label" => "Text",
        "type" => "textarea",
        "uploads" => false,
    ],

    "line6" => ["type" => "line"],

    "valuesHeadline" => [
        "label" => "Engagement",
        "type" => "headline",
    ],

    "titleValues" => [
        "label" => "Title",
        "type" => "text",
    ],

    "textValues" => [
        "label" => "Text",
        "type" => "textarea",
        "uploads" => false,
    ],

    "lstValues" => [
        "label" => "Liste",
        "type" => "structure",
        "fields" => [
            "title" => [
                "label" => "Titre",
                "type" => "text",
            ],
            "icon" => ["label" => "Icon"] + $image,
        ],
    ],

    "line7" => ["type" => "line"],

    "footerHeadline" => [
        "label" => "Footer",
        "type" => "headline",
    ],

    "textFooter1" => [
        "label" => "Text 1",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "textFooter2" => [
        "label" => "Text 2",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "textFooter3" => [
        "label" => "Text 3",
        "type" => "textarea",
        "width" => "1/2",
        "uploads" => false,
    ],

    "btnFooter1" =>
        [
            "label" => "Bouton 1",
            "help" =>
                "Le lien est remplacé automatiquement par le PDF publié depuis « Menu »",
        ] + $button,

    "btnFooter2" => ["label" => "Bouton 2"] + $button,
];
