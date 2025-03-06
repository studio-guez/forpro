<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Cocktail;

return [
    'pattern' => 'menu/cocktail/title',
    'load'    => function () {
        $name = Metadata::get(Cocktail::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(Cocktail::name(), 'name', $name ?? "");
    }
];
