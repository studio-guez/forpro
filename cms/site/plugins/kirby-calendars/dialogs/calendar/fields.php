<?php

return [
    'name' => [
        'label' => 'Nom',
        'type' => 'text'
    ],
    'email' => [
        'label' => 'Email',
        'type' => 'email',
        'help' => 'Email qui recevra les notifications des évènements confirmés'
    ],
    'min_days_before_rdvs' => [
        'label' => 'Jours minimum avant un rendez-vous',
        'type' => 'number',
        'help' => '0 pour aucun minimum'
    ],
    'max_events_per_day' => [
        'label' => 'Nombre maximum de rendez-vous par jour',
        'type' => 'number',
        'help' => '0 pour illimité'
    ],
    'max_events_per_slot' => [
        'label' => 'Nombre maximum de rendez-vous par plage horaire',
        'type' => 'number',
        'help' => '0 pour illimité'
    ],
];
