<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\MainCourse;

return [
    'pattern' => 'menu/maincourse/title',
    'load'    => function () {
        $name = Metadata::get(MainCourse::name(), 'name') ?? "";
        $value = ['name' => $name];

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'name' => [
                        'label' => 'Titre de section',
                        'type' => 'text',
                    ],
                ],
                'value' => $value
            ]
        ];
    },
    'submit' => function () {
        $name = get('name');
        return Metadata::addOrUpdate(MainCourse::name(), 'name', $name ?? "");
    }
];
