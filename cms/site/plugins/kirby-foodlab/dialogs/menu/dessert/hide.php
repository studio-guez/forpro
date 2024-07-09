<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\Dessert;

return [
    'pattern' => 'menu/hotdrink/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Dessert::name(), 'hidden') != '' ? Metadata::get(Dessert::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Dessert::name(), 'hidden', !$hidden);
    }
];
