<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Spirit;

return [
    'pattern' => 'menu/spirit/title',
    'load'    => function () {
        $name = Metadata::get(Spirit::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(Spirit::name(), 'name', $name ?? "");
    }
];
