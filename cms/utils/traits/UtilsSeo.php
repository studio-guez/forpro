<?php

/**
 * Kirby SEO metadata cascade -> frontend head payload, plus the page set the
 * site-wide discovery documents (sitemap, llms.txt) are built from.
 */
trait UtilsSeo
{
    /**
     * Templates that have a route on the decoupled frontend. Wider than
     * `UtilsSearch::SEARCHABLE_TEMPLATES`: the index pages are listed here
     * because they are destinations, even though they carry no text of their
     * own. The structural containers and the taxonomy terms have no URL.
     */
    private const INDEXABLE_TEMPLATES = [
        'page',
        'basic-page',
        'faq',
        'team',
        'press',
        'impressum',
        'factory-lab',
        'events',
        'event',
        'projects',
        'project',
        'job-offers',
        'job-offer',
        'missions',
        'mission',
    ];

    /**
     * Every page a crawler may be pointed at: it has a frontend URL, its SEO
     * settings let it be indexed, and — for a job offer or a mission — it is
     * still open to applications. The source of truth for both the sitemap and
     * llms.txt, so the two can never advertise different sets of pages.
     */
    static function getIndexablePages(): \Kirby\Cms\Pages
    {
        return site()->index()->filter(
            fn(\Kirby\Cms\Page $page) => in_array($page->intendedTemplate()->name(), self::INDEXABLE_TEMPLATES, true)
                && $page->metadata()->robotsIndex()->toBool()
                && self::isOpenToApplications($page)
        );
    }

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
