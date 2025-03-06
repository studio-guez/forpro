<?php

use Eclypsys\Menu\Metadata;
use Eclypsys\Menu\BubbleWine;

return [
    'pattern' => 'menu/bubblewine/title',
    'load'    => function () {
        $name = Metadata::get(BubbleWine::name(), 'name') ?? "";
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
        return Metadata::addOrUpdate(BubbleWine::name(), 'name', $name ?? "");
    }
];
