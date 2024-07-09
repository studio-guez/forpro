<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\SoftDrink;

return [
    'pattern' => 'menu/softdrink/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(SoftDrink::name(), 'hidden') != '' ? Metadata::get(SoftDrink::name(), 'hidden') : false;
        return Metadata::addOrUpdate(SoftDrink::name(), 'hidden', !$hidden);
    }
];
