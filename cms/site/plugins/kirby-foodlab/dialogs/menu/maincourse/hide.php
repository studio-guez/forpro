<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\MainCourse;

return [
    'pattern' => 'menu/maincourse/hide',
    'load' => function () {
        return [
            'component' => 'k-text-dialog',
            'props' => [
                'text' => 'Rendre cette section invisible ?',
            ]
        ];
    },
    'submit' => function () {
        $hidden = Metadata::get(MainCourse::name(), 'hidden') != '' ? Metadata::get(MainCourse::name(), 'hidden') : false;
        return Metadata::addOrUpdate(MainCourse::name(), 'hidden', !$hidden);
    }
];
