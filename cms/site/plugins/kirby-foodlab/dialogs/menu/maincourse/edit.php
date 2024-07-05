<?php

use MediumSans\Menu\Maincourse;

return [
    'pattern' => 'menu/maincourse/(:any)/edit',
    'load'    => function (string $id) {
        $maincourse = Maincourse::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $maincourse
            ]
        ];
    },
    'submit' => function (string $id) {
        return Maincourse::update($id, get());
    }
];
