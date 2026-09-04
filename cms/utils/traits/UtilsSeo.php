<?php

/**
 * Kirby SEO metadata cascade -> frontend head payload.
 */
trait UtilsSeo
{
    /**
     * Resolves the page metadata through Kirby SEO's cascade
     * (page fields -> parent -> site -> plugin defaults) and returns it
     * in the shape consumed by the SvelteKit frontend.
     */
    static function getSeoDataFromPage(\Kirby\Cms\Page $kirbyPage): array
    {
        $meta = $kirbyPage->metadata();

        $data = [
            // General
            'title'           => $meta->metaTitle()->value(),
            'description'     => $meta->get('metaDescription')->value(),
            // Not the plugin's own `canonicalUrl()`: it derives the URL from
            // the Kirby page, but a page is published under its `virtualPath`,
            // so `/pages/<id>` does not exist on the frontend.
            'canonicalUrl'    => $kirbyPage->frontendUrl(),
            'robots'          => $meta->robots(),
            'locale'          => $meta->get('lang')->value(),
            // Open Graph
            'ogTitle'         => $meta->ogTitle()->value(),
            'ogDescription'   => $meta->get('ogDescription')->value(),
            'ogSiteName'      => $meta->get('ogSiteName')->value(),
            'ogType'          => $meta->get('ogType')->value(),
            'ogImage'         => self::getOgImage($kirbyPage, $meta),
            // Twitter
            'twitterCardType' => $meta->get('twitterCardType')->value(),
            'twitterSite'     => $meta->twitterSite()->value(),
            'twitterCreator'  => $meta->get('twitterCreator')->value(),
        ];

        // Schema.org JSON-LD, built out of the payload above so the structured
        // data can never contradict the meta tags. See `UtilsSchema`.
        $data['schemas'] = option('tobimori.seo.generateSchema', false)
            ? self::getPageSchemas($kirbyPage, $data)
            : [];

        $data['trackWithMatomo'] = $kirbyPage->trackWithMatomo()->toBool();

        return $data;
    }

    /**
     * The picture a shared link shows: the social image resolved through the
     * SEO cascade, or -- much more often, since it is one field fewer to fill --
     * the cover of the page itself. Null on the templates that have neither.
     *
     * Built here rather than through `$meta->ogImage()` so the crop can be
     * emitted as JPEG: the site-wide thumb default is WebP, which several
     * social networks still refuse to render.
     */
    private static function getOgImage(\Kirby\Cms\Page $page, \tobimori\Seo\Meta $meta): ?string
    {
        $field = $meta->get('ogImage');

        if ($file = $field->toFile() ?? $page->cover()->toFile()) {
            return $file->thumb([
                'width'  => 1200,
                'height' => 630,
                'crop'   => true,
                'format' => 'jpeg',
            ])->url();
        }

        // The field can also hold the URL of an image hosted elsewhere.
        return $field->isNotEmpty() ? $field->value() : null;
    }
}
