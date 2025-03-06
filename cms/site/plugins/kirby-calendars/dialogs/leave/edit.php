<?php

use Eclypsys\KirbyCalendars\Leave;

return [
    'pattern' => 'leave/(:any)/edit',
    'load'    => function (string $id) {
        $leave = Leave::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value' => $leave
            ]
        ];
    },
    'submit' => function (string $id) {
        return Leave::update($id, get());
    }
];
