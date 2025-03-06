<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Cocktail::name(), 'hidden') != '' ? Metadata::get(Cocktail::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Cocktail::name(), 'hidden', !$hidden);
    }
];
