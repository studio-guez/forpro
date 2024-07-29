<?php

return [
    'option' => [
        'label' => 'Est-ce une choix de plat ?',
        'type' => 'toggle',
        'text' => [ 'Non', 'Oui']
    ],
    'name1' => [
        'label' => 'Nom',
        'type' => 'text'
    ],
    'description1' => [
        'label' => 'Description',
        'type' => 'text',
    ],
    'info' => [
        'type' => 'info',
        'text' => 'OU',
        'theme' => 'passive',
        'when' => [
            'option' => true,
        ],
    ],
    'name2' => [
        'label' => 'Nom',
        'type' => 'text',
        'when' => [
            'option' => true,
        ],
    ],
    'description2' => [
        'label' => 'Description',
        'type' => 'text',
        'when' => [
            'option' => true,
        ],
    ],
];
