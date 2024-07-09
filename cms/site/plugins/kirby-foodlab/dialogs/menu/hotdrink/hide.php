<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\HotDrink;

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
        $hidden = Metadata::get(HotDrink::name(), 'hidden') != '' ? Metadata::get(HotDrink::name(), 'hidden') : false;
        return Metadata::addOrUpdate(HotDrink::name(), 'hidden', !$hidden);
    }
];
