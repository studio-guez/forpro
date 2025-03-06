<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\Starter;

return [
    'pattern' => 'menu/starter/title',
    'load'    => function () {
        $name = Metadata::get(Starter::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(Starter::name(), 'name', $name ?? "");
    }
];
