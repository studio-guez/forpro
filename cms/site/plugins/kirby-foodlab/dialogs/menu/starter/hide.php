<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\Starter;

return [
    'pattern' => 'menu/starter/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(Starter::name(), 'hidden') != '' ? Metadata::get(Starter::name(), 'hidden') : false;
        return Metadata::addOrUpdate(Starter::name(), 'hidden', !$hidden);
    }
];
