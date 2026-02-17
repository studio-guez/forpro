<?php

$daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

$fields = [
    'date' => [
        'label' => 'Date (le jours sélectionné représente la semaine entière)',
        'type' => 'date',
        'time'    => false,
    ],
    'stations_info' => [
        'label' => 'Noms des stations',
        'type' => 'info',
        'text' => 'Noms des 4 stations pour les 5 jours suivant la date sélectionnée ci-dessus. Les stations sont de gauche à droite lorsqu\'on est devant les écrans.',
        'theme' => 'info',
    ],
    'station1_name' => [
        'label' => 'Station 1',
        'type' => 'text',
        'width' => '1/4',
    ],
    'station2_name' => [
        'label' => 'Station 2',
        'type' => 'text',
        'width' => '1/4',
    ],
    'station3_name' => [
        'label' => 'Station 3',
        'type' => 'text',
        'width' => '1/4',
    ],
    'station4_name' => [
        'label' => 'Station 4',
        'type' => 'text',
        'width' => '1/4',
    ],
];

for ($jour = 1; $jour <= 5; $jour++) {
    $fields["jour{$jour}_info"] = [
        'label' => $daysOfWeek[$jour - 1],
        'type' => 'headline',
    ];

    for ($station = 1; $station <= 4; $station++) {
        $fields["jour{$jour}_station{$station}_info"] = [
            'label' => " ",
            'type' => 'info',
            'text' => "Station $station",
            'theme' => 'none',
        ];

        $fields["jour{$jour}_station{$station}_menu"] = [
            'label' => 'Menu',
            'type' => 'writer',
            'marks' => ['italic'],
            'nodes' => false,
            'inline' => true,
            'width' => '4/12'
        ];

        $fields["jour{$jour}_station{$station}_description"] = [
            'label' => 'Description',
            'type' => 'writer',
            'marks' => ['italic'],
            'nodes' => false,
            'inline' => true,
            'width' => '4/12'
        ];

        $fields["jour{$jour}_station{$station}_prix_public"] = [
            'label' => 'Prix public',
            'type' => 'number',
            'step' => 0.10,
            'width' => '2/12',
        ];

        $fields["jour{$jour}_station{$station}_prix_apprenti"] = [
            'label' => "Prix apprenti\u{00B7}e\u{00B7}s",
            'type' => 'number',
            'step' => 0.10,
            'width' => '2/12',
        ];
    }
}

return $fields;
