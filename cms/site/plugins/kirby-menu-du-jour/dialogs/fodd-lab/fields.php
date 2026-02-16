<?php

$daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

$fields = [
    'date' => [
      'label' => 'Date (le jours sélectionné représente la semaine entière)',
      'type' => 'date',
      'time'    => false,
      'width' => '1/2',
    ],
    'prix' => [
        'label' => 'Prix unique sur toute la semaine',
        'type' => 'number',
        'min' => 0,
        'placeholder' => '0.00',
        'step' => '0.5',
        'width' => '1/2'
    ],
];


for ($jour = 1; $jour <= 5; $jour++) {
  $fields["jour{$jour}_info"] = [
    'label' => $daysOfWeek[$jour - 1],
    'type' => 'headline',
    'width' => '2/2'
  ];

  $fields["jour{$jour}_menu"] = [
    'label' => 'Menu du jour',
    'type' => 'writer',
    'marks' => ['italic'],
    'nodes' => false,
    'inline' => true,
    'width' => '1/2'
  ];
}


return $fields;
