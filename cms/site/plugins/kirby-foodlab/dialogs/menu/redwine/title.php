<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\RedWine;

return [
    'pattern' => 'menu/redwine/title',
    'load'    => function () {
        $name = Metadata::get(RedWine::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(RedWine::name(), 'name', $name ?? "");
    }
];
