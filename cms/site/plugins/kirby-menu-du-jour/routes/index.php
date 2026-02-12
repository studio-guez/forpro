<?php

use Kirby\Data\Json;
use Kirby\Http\Response;
use Eclypsys\MenuDuJour\MenuDuJour;

return [
    [
        'pattern' => 'menu-du-jour',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function () {
            kirby()->impersonate('kirby');

            return Response::json(
                Json::encode(MenuDuJour::list())
            );
        }
    ]
];
