<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\HotDrink;

return [
    'pattern' => 'menu/hotdrink/title',
    'load'    => function () {
        $name = Metadata::get(HotDrink::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(HotDrink::name(), 'name', $name ?? "");
    }
];
