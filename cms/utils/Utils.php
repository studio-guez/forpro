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

    static function muteImageFilesDataIfBlocksHasKeyValue(string $contentTypeKey, &$content): void
    {
        if(!isset($content['content'][$contentTypeKey])) return;

        foreach ($content['content'][$contentTypeKey] as &$itemArray) {
            //todo: images with s for profiles importation | change images to image in dataBase and profiles json result
            if(isset($itemArray['images']))    $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'images');
            if(isset($itemArray['image']))     $itemArray['imageData'] = self::getImageArrayDataInArray($itemArray, 'image');
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
            'url'           => $file->resize(1280)->url(),
            'srcset'        => $file->srcset('default'),
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
}
