<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\SoftDrink;

return [
    'pattern' => 'menu/softdrink/title',
    'load'    => function () {
        $name = Metadata::get(SoftDrink::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(SoftDrink::name(), 'name', $name ?? "");
    }
];
