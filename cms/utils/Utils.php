<?php

class Utils
{
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
        $getImageArrayData = Utils::getImageArrayDataInPage(new \Kirby\Cms\Files($itemArray[$keyNameForImage]));
        return $itemArray['imageData'] = array_values($getImageArrayData);
    }


    static function getJsonEncodeImageData(\Kirby\Cms\File $file): array
    {
        return [
            'focus' => $file->content()->focus()->value(),
            'caption'       => $file->caption()->value(),
            'alt'           => $file->alt()->value(),
            'link'          => $file->link()->value(),
            'photoCredit'   => $file->photoCredit()->value(),
            // `width`/`height` are the intrinsic dimensions so the frontend can
            // reserve space (avoid CLS). `url` is a mid-size WebP fallback for
            // `src`; `srcset` lets the browser pick per viewport × pixel density.
            'width'         => $file->width(),
            'height'        => $file->height(),
            'url'           => $file->resize(1920)->url(),
            'srcset'        => $file->srcset('default'),
        ];
    }

    /**
     * Resolves the URL from a structure item using the type/page/url pattern.
     */
    static function resolvePageOrUrlItem(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            if ($linkedPage) {
                return $linkedPage->isHomePage() ? '/' : '/' . $linkedPage->virtualPath();
            }
            return null;
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves the label from a structure item, falling back to the page title or URL.
     */
    static function resolvePageOrUrlLabel(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->label()->isNotEmpty()) {
            return $item->label()->value();
        }
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            return $linkedPage?->title()->value();
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves a CTA structure field (max 1) to an array for JSON output, or null if empty.
     */
    static function resolveCtaStructure(\Kirby\Content\Field $field): ?array
    {
        $item = $field->toStructure()->first();
        if (!$item || $item->label()->isEmpty()) return null;
        $url = self::resolvePageOrUrlItem($item);
        if (!$url) return null;
        return [
            'label' => $item->label()->value(),
            'url'   => $url,
            'icon'  => $item->icon()->isNotEmpty() ? $item->icon()->value() : null,
        ];
    }

    /**
     * Resolves the page metadata through Kirby SEO's cascade
     * (page fields -> parent -> site -> plugin defaults) and returns it
     * in the shape consumed by the SvelteKit frontend.
     */
    static function getSeoDataFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $meta = $kirbyPage->metadata();

        $schemas = [];
        if (option('tobimori.seo.generateSchema', false)) {
            // The hook (page.render:before) only builds WebSite with page-level
            // data, which is wrong for content pages. Build WebPage ourselves.
            $webPage = $kirbyPage->schema('WebPage')
                ->url($meta->canonicalUrl())
                ->name($meta->metaTitle()->value())
                ->description($meta->get('metaDescription')->value());
            if ($ogImage = $meta->ogImage()) {
                $webPage->image($ogImage);
            }
            // Serialize only the WebPage schema; skip WebSite (built by hook).
            $schemas = [json_decode(json_encode($webPage), true)];
        }

        return [
            // General
            'title'           => $meta->metaTitle()->value(),
            'description'     => $meta->get('metaDescription')->value(),
            'canonicalUrl'    => $meta->canonicalUrl(),
            'robots'          => $meta->robots(),
            'locale'          => $meta->get('lang')->value(),
            // Open Graph
            'ogTitle'         => $meta->ogTitle()->value(),
            'ogDescription'   => $meta->get('ogDescription')->value(),
            'ogSiteName'      => $meta->get('ogSiteName')->value(),
            'ogType'          => $meta->get('ogType')->value(),
            'ogImage'         => $meta->ogImage(),
            // Twitter
            'twitterCardType' => $meta->get('twitterCardType')->value(),
            'twitterSite'     => $meta->twitterSite()->value(),
            'twitterCreator'  => $meta->get('twitterCreator')->value(),
            // Schema.org JSON-LD
            'schemas'         => $schemas,
        ];
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
