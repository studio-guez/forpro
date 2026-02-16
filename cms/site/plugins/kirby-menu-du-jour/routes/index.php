<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use Eclypsys\MenuDuJour\MenuDuJour;
use Eclypsys\MenuDuJour\FoddLab;
use Eclypsys\MenuDuJour\SliderImages;

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
                Json::encode(FoddLab::list())
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
