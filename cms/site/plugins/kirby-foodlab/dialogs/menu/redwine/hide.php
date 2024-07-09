<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\RedWine;

return [
    'pattern' => 'menu/redwine/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(RedWine::name(), 'hidden') != '' ? Metadata::get(RedWine::name(), 'hidden') : false;
        return Metadata::addOrUpdate(RedWine::name(), 'hidden', !$hidden);
    }
];
