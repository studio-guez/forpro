<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use Villa1203\MenuDuJour\MenuDuJour;
use Villa1203\MenuDuJour\FoddLab;
use Villa1203\MenuDuJour\SliderImages;

return [
    [
        'pattern' => 'foodcourt',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function () {
            kirby()->impersonate('kirby');

            return Response::json(
                Json::encode(MenuDuJour::list())
            );
        }
    ],
    [
        'pattern' => 'foodlab',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function () {
            kirby()->impersonate('kirby');

            return Response::json(
              array_merge(
                ['menus' => FoddLab::list()],
                ['footer' => FoddLab::getTexte()]
              )
            );
        }
    ],
    [
        'pattern' => 'slider-images',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function () {
            kirby()->impersonate('kirby');

            return Response::json(
                Json::encode(SliderImages::list())
            );
        }
    ],
    [
        'pattern' => 'slider-images/(:any)',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function (string $filename) {
            return SliderImages::serve($filename);
        }
    ]
];
