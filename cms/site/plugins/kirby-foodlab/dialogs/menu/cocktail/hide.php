<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Cocktail::name(), 'hidden') != '' ? Metadata::get(Cocktail::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Cocktail::name(), 'hidden', !$hidden);
    }
];
