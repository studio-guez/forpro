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
