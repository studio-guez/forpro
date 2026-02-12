<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use Eclypsys\MenuDuJour\MenuDuJour;
use Eclypsys\MenuDuJour\FoddLab;

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
    ]
];
