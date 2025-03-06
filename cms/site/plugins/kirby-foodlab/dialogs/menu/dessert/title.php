<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Dessert;

return [
    'pattern' => 'menu/dessert/title',
    'load'    => function () {
        $name = Metadata::get(Dessert::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(Dessert::name(), 'name', $name ?? "");
    }
];
