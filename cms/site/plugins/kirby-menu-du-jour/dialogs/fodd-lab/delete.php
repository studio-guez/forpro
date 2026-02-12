<?php

use Eclypsys\MenuDuJour\FoddLab;

return [
    'pattern' => 'fodd-lab/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            ]
        ];
    },
    'submit' => function (string $id) {
        return FoddLab::delete($id);
    }
];
