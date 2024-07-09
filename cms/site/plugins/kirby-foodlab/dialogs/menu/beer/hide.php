<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\Beer;

return [
    'pattern' => 'menu/beer/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Beer::name(), 'hidden') != '' ? Metadata::get(Beer::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Beer::name(), 'hidden', !$hidden);
    }
];
