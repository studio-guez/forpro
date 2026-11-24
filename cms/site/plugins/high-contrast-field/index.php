<?php

/**
 * Field type of `fields/highContrast`: a `files` field that hides itself on
 * video files, which have no high-contrast version in the frontend.
 */
Kirby::plugin('forpro/high-contrast-field', [
    'fields' => [
        'highContrastFiles' => [
            'extends' => 'files',
            'props' => [
                /**
                 * `hidden` is read from the field type, not from the file, so `when`
                 * is the only visibility switch that can differ per file. The Panel
                 * hides a field whose condition is not met, and no form has this key.
                 */
                'when' => function ($when = null) {
                    $model = $this->model();

                    if ($model instanceof \Kirby\Cms\File && $model->type() === 'video') {
                        return ['highContrastOnVideo' => true];
                    }

                    return $when;
                },
            ],
        ],
    ],
]);
