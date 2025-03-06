<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Beer;

return [
    'pattern' => 'menu/beer/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Beer::name(), 'hidden') != '' ? Metadata::get(Beer::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Beer::name(), 'hidden', !$hidden);
    }
];
