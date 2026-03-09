<?php

use Villa1203\MenuDuJour\MenuDuJour;
use Villa1203\MenuDuJour\FoddLab;
use Villa1203\MenuDuJour\SliderImages;

return [
    'pattern' => 'kirby-menu-du-jour',
    'action'  => function () {
        return [
            'component' => 'k-menu-du-jour-view',
            'props' => [
                'items' => MenuDuJour::list(),
                'foddLabItems' => FoddLab::list(),
                'foodcourtTexte' => FoddLab::getTexte(),
                'sliderImages' => SliderImages::list(),
            ]
        ];
    }
];
