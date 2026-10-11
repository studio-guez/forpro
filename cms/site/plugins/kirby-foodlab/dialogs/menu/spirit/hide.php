<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Spirit;

return [
    'pattern' => 'menu/spirit/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Spirit::name(), 'hidden') != '' ? Metadata::get(Spirit::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Spirit::name(), 'hidden', !$hidden);
    }
];
