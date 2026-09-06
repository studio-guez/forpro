<?php

/**
 * Fields of the restaurant content form, a straight port of the former
 * `blueprints/tabs/restaurant.yml`. They are fed to `Kirby\Form\Form`
 * (see `Eclypsys\Restaurant::form()`), so this is a blueprint in every way
 * except that it is not attached to a page.
 *
 * The keys are also the keys stored in data/restaurant.json.
 *
 * Two things differ from the old blueprint, both because a custom panel view
 * has no model behind it:
 *   - `files` fields are `restaurantfiles` fields (see restaurantfiles.php);
 *   - `link` fields are `restaurantlink` fields (see restaurantlink.php):
 *     `page` is dropped, and `file` is replaced by a `media` type picking
 *     from the restaurant media library.
 */

$image = [
    "type" => "restaurantfiles",
    "uploads" => [
        "accept" => "image/jpeg,image/png,image/gif,image/webp,image/svg+xml",
    ],
];

$link = [
    "type" => "restaurantlink",
    "options" => ["url", "email", "tel", "anchor", "custom"],
];

/**
 * The file button of the toolbar picks from the same media library as the
 * image fields, see `restaurant/fields/(:any)/files` in routes/index.php.
 *
 * `uploads` stays off: uploading straight from the toolbar makes the panel
 * refresh `$panel.content`, the model content state this view does not use.
 * New media is added through the image fields.
 */
$textarea = [
    "type" => "textarea",
    "uploads" => false,
];

// the old blueprint left these unlabelled, which reads as an empty field
$button = [
    "type" => "object",
    "width" => "1/2",
    "fields" => [
        "link" => ["label" => "url"] + $link,
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
        "marks" => ["link"],
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
                "help" => "les lien d'ancrages disponible sur le site sont
- \\#hero
- \\#lefood
- \\#lelab
- \\#equipe-formation
- \\#foodcourt-popup-cafe
- \\#engagements
",
            ] + $link,
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
        "width" => "1/2",
    ] + $textarea,

    "line1" => ["type" => "line"],

    "lefood" => [
        "label" => "Le Food",
        "type" => "headline",
    ],

    "titleFood" => [
        "label" => "Title 1",
        "type" => "text",
        "width" => "1",
    ],

    "textFood" => [
        "label" => "Text Hero 1",
        "width" => "1/2",
    ] + $textarea,

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
        "width" => "1",
    ],

    "fileLab1" => ["label" => "Image 1", "width" => "1/2"] + $image,

    "textLab" => [
        "label" => "Text Hero 1",
        "width" => "1/2",
    ] + $textarea,

    "btnLab" => ["label" => "Bouton"] + $button,

    "line3" => ["type" => "line"],

    "picture1" => [
        "label" => "Image 1",
        "width" => "1",
    ] + $image,

    "line4" => ["type" => "line"],

    "formation" => [
        "label" => "Equipe & Formation",
        "type" => "headline",
    ],

    "titleFormation" => [
        "label" => "Title 1",
        "type" => "text",
        "width" => "1",
    ],

    "textFormation" => [
        "label" => "Text Hero 1",
        "width" => "1/2",
    ] + $textarea,

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
        "width" => "1",
    ],

    "subtitleUnivers" => [
        "label" => "Subtitle",
        "type" => "text",
        "width" => "1",
    ],

    "blogUniversTitle1" => [
        "label" => "Title",
        "type" => "text",
        "width" => "1/2",
    ],

    "blogUniversFil1" => ["label" => "Illustration", "width" => "1/2"] + $image,

    "blogUniversText1" => [
        "label" => "Text",
        "width" => "1",
    ] + $textarea,

    "blogUniversTitle2" => [
        "label" => "Title",
        "type" => "text",
        "width" => "1/2",
    ],

    "blogUniversFile2" => ["label" => "Illustration", "width" => "1/2"] + $image,

    "blogUniversText2" => [
        "label" => "Text",
        "width" => "1",
    ] + $textarea,

    "line6" => ["type" => "line"],

    "valuesHeadline" => [
        "label" => "Engagement",
        "type" => "headline",
    ],

    "titleValues" => [
        "label" => "Title",
        "type" => "text",
        "width" => "1",
    ],

    "textValues" => [
        "label" => "Text",
        "width" => "1",
    ] + $textarea,

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
        "width" => "1/2",
    ] + $textarea,

    "textFooter2" => [
        "label" => "Text 2",
        "width" => "1/2",
    ] + $textarea,

    "textFooter3" => [
        "label" => "Text 3",
        "width" => "1/2",
    ] + $textarea,

    "btnFooter1" => ["label" => "Bouton 1"] + $button,

    "btnFooter2" => ["label" => "Bouton 2"] + $button,

    "menuPdf" => [
        "label" => "Menu PDF",
        "type" => "restaurantfiles",
        "uploads" => ["accept" => "application/pdf"],
        "help" =>
            'Remplit automatiquement lors de la génération du menu avec image et fond dans "Menu"',
    ],
];
