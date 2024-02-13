<?php

use MediumSans\KirbyCalendars\Service;

return [
    'pattern' => 'service/(:any)/edit',
    'load'    => function (string $id) {
        $service = Service::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $service
            ]
        ];
    },
    'submit' => function (string $id) {
        return Service::update($id, get());
    }
];
