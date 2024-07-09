<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\WhiteWine;

return [
    'pattern' => 'menu/whitewine/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(WhiteWine::name(), 'hidden') != '' ? Metadata::get(WhiteWine::name(), 'hidden') : false;
        return Metadata::addOrUpdate(WhiteWine::name(), 'hidden', !$hidden);
    }
];
