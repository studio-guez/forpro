<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\MainCourse;

return [
    'pattern' => 'menu/maincourse/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible/visible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(MainCourse::name(), 'hidden') != '' ? Metadata::get(MainCourse::name(), 'hidden') : false;
        return Metadata::addOrUpdate(MainCourse::name(), 'hidden', !$hidden);
    }
];
