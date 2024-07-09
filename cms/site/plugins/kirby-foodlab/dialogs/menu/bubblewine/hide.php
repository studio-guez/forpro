<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\BubbleWine;

return [
    'pattern' => 'menu/bubblewine/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(BubbleWine::name(), 'hidden') != '' ? Metadata::get(BubbleWine::name(), 'hidden') : false;
        return Metadata::addOrUpdate(BubbleWine::name(), 'hidden', !$hidden);
    }
];
