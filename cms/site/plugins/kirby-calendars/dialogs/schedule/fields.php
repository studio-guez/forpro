<?php

return [
    'day_id' => [
        'label' => 'Jour',
        'type' => 'select',
        'required' => true,
        'options' => [
            [
                'text' => 'Lundi',
                'value' => '1'
            ],
            [
                'text' => 'Mardi',
                'value' => '2'
            ],
            [
                'text' => 'Mercredi',
                'value' => '3'
            ],
            [
                'text' => 'Jeudi',
                'value' => '4'
            ],
            [
                'text' => 'Vendredi',
                'value' => '5'
            ],
            [
                'text' => 'Samedi',
                'value' => '6'
            ],
            [
                'text' => 'Dimanche',
                'value' => '0'
            ],
        ]
    ],
    'opening_hour' => [
        'label' => 'Ouverture',
        'type' => 'time',
    ],
    'closing_hour' => [
        'label' => 'Fermeture',
        'type' => 'time',
    ],
    'is_closed' => [
        'label' => 'Fermé ?',
        'type' => 'toggle',
    ],
];
