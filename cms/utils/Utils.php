<?php

class Utils {
    static function getHeroFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $hero = $kirbyPage->hero()->toStructure()?->get(0);

        return $hero ? [
            'text' => $hero->text()->value(),
            'backgroundcolor' => $hero->backgroundcolor()->value(),
            'textcolor' => $hero->textcolor()->value(),
        ] : [];
    }

    static function getImageArrayDataInPage(\Kirby\Cms\Files $files): array|null
    {
        return $files->map(function (\Kirby\Cms\File $item): array {
            return self::getJsonEncodeImageData($item);
        })->data();
    }

    static function getJsonEncodeImageData(\Kirby\Cms\File $file): array
    {
        return [
            'caption'       => $file->caption()->value(),
            'alt'           => $file->alt()->value(),
            'link'          => $file->link()->value(),
            'photoCredit'   => $file->photoCredit()->value(),
            'url'           => $file->url(),
            'mediaUrl'      => $file->mediaUrl(),
            'width'         => $file->width(),
            'height'        => $file->height(),
            'resize'        => [
                'tiny'          => $file->resize(50, null, 10)->url(),
                'small'         => $file->resize(500)->url(),
                'reg'           => $file->resize(1280)->url(),
                'large'         => $file->resize(1920)->url(),
                'xxl'           => $file->resize(2500)->url(),
            ]
        ];
    }
}
