<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\RedWine;

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
