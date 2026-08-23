<?php

/**
 * Image / video / favicon serialization shared by every JSON template.
 */
trait UtilsMedia
{
    static function getImageArrayDataInPage(\Kirby\Cms\Files $files): array|null
    {
        return $files->map(function (\Kirby\Cms\File $item): array {
            return self::getJsonEncodeImageData($item);
        })->data();
    }

    static function muteImageFilesDataIfBlocksHasKeyValue(string $contentTypeKey, &$content): void
    {
        if (!isset($content['content'][$contentTypeKey])) return;

        foreach ($content['content'][$contentTypeKey] as &$itemArray) {
            //todo: images with s for profiles importation | change images to image in dataBase and profiles json result
            if (isset($itemArray['images']))    $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'images');
            if (isset($itemArray['image']))     $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'image');
        }
    }

    static function getImageArrayDataInArray(array &$itemArray, string $keyNameForImage): array
    {
        $getImageArrayData = self::getImageArrayDataInPage(new \Kirby\Cms\Files($itemArray[$keyNameForImage]));
        return $itemArray['imageData'] = array_values($getImageArrayData);
    }

    /**
     * `{focus, caption, alt, link, photoCredit, width, height, url, srcset}` shape
     * shared by every image payload; `$rendition` supplies the sizing/urls and
     * can override any metadata key.
     */
    private static function getImageData(\Kirby\Cms\File $file, array $rendition): array
    {
        return [
            'focus'         => $file->content()->focus()->value(),
            'caption'       => $file->caption()->value(),
            'alt'           => $file->alt()->value(),
            'link'          => $file->link()->value(),
            'photoCredit'   => $file->photoCredit()->value(),
            ...$rendition,
        ];
    }

    static function getJsonEncodeImageData(\Kirby\Cms\File $file): array
    {
        return self::getImageData($file, [
            // `width`/`height` are the intrinsic dimensions so the frontend can
            // reserve space (avoid CLS). `url` is a mid-size WebP fallback for
            // `src`; `srcset` lets the browser pick per viewport × pixel density.
            'width'         => $file->width(),
            'height'        => $file->height(),
            'url'           => $file->resize(1920)->url(),
            'srcset'        => $file->srcset('default'),
        ]);
    }

    /**
     * Serializes a single media file (image or video) for JSON output.
     * Videos can't be resized/srcset, so they only expose a direct URL + mime.
     */
    static function getJsonEncodeMediaData(\Kirby\Cms\File $file): array
    {
        if ($file->type() === 'video') {
            return [
                'type'          => 'video',
                'alt'           => $file->alt()->value(),
                'caption'       => $file->caption()->value(),
                'photoCredit'   => $file->photoCredit()->value(),
                'link'          => $file->link()->value(),
                'url'           => $file->url(),
                'mime'          => $file->mime(),
            ];
        }

        return ['type' => 'image'] + self::getJsonEncodeImageData($file);
    }

    /**
     * Serializes a media Files collection (image or video) to a list for JSON output.
     */
    static function getJsonEncodeMediaArray(\Kirby\Cms\Files $files): array
    {
        return array_values($files->map(fn(\Kirby\Cms\File $file) => self::getJsonEncodeMediaData($file))->data());
    }

    /**
     * Serializes an optional file (e.g. a `cover` that may not be set).
     */
    static function getJsonEncodeImageDataOrNull(?\Kirby\Cms\File $file): ?array
    {
        return $file ? self::getJsonEncodeImageData($file) : null;
    }

    /**
     * Builds the favicon payload for the frontend head.
     * SVGs are served as-is (scalable, one per color scheme); the 512×512 PNG
     * masters are downscaled to every size in `option('favicon.resize')` so the
     * frontend can emit all standard favicon `<link>` tags.
     */
    static function getFaviconData(\Kirby\Cms\Site $site): array
    {
        $sizes = option('favicon.resize', [16, 32, 48, 180, 192, 512]);

        $variant = function (?\Kirby\Cms\File $svg, ?\Kirby\Cms\File $png) use ($sizes): array {
            return [
                'svg' => $svg?->url(),
                'png' => $png
                    ? array_map(fn(int $size) => [
                        'size' => $size,
                        'url'  => $png->resize($size)->url(),
                    ], $sizes)
                    : [],
            ];
        };

        return [
            'light' => $variant(
                $site->faviconLightSvg()->toFile(),
                $site->faviconLightPng()->toFile()
            ),
            'dark' => $variant(
                $site->faviconDarkSvg()->toFile(),
                $site->faviconDarkPng()->toFile()
            ),
        ];
    }
}
