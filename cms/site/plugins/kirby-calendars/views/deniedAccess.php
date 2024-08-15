<?php

return [
    'pattern' => 'kirby-calendars/denied-access',
    'action'  => function () {
        return [
            'component' => 'k-denied-access',
            'props' => [
                'msg' => "Vous n'avez pas accès à cette page.",
            ]
        ];
    }
];
