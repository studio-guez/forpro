<?php

use MediumSans\Menu\Metadata;
use MediumSans\Menu\WhiteWine;

return [
    'pattern' => 'menu/whitewine/title',
    'load'    => function () {
        $name = Metadata::get(WhiteWine::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(WhiteWine::name(), 'name', $name ?? "");
    }
];
