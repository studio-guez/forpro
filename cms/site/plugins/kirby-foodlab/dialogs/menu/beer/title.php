<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Beer;

return [
    'pattern' => 'menu/beer/title',
    'load'    => function () {
        $name = Metadata::get(Beer::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(Beer::name(), 'name', $name ?? "");
    }
];
