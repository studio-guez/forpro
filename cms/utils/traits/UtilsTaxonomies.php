<?php

/**
 * Taxonomy term resolution, filtering and query building.
 */
trait UtilsTaxonomies
{
    /**
     * Resolves taxonomy term UUIDs stored in a tags field to a stable
     * `{slug, title}` representation. Terms are stored as UUIDs (e.g.
     * `page://xxx`) so selections survive slug changes; each UUID is resolved
     * to its term page under content/taxonomies/<taxonomy> here. Unresolvable
     * UUIDs (e.g. deleted terms) are skipped.
     */
    static function resolveTaxonomyTerms(\Kirby\Content\Field $field, string $taxonomy): array
    {
        return array_values(array_filter(array_map(
            fn(string $uuid): ?array => ($term = page($uuid)) ? self::getTaxonomyTermData($term) : null,
            $field->split(',')
        )));
    }

    /**
     * `{slug, title, color}` shape shared by every taxonomy term payload.
     */
    private static function getTaxonomyTermData(\Kirby\Cms\Page $term): array
    {
        return [
            'slug'  => $term->slug(),
            'title' => $term->title()->value(),
            'color' => $term->color()->isNotEmpty() ? $term->color()->value() : null,
        ];
    }

    /**
     * Returns all terms of a taxonomy in their CMS-defined order (the order of
     * the term pages under content/taxonomies/<taxonomy>), in the same
     * `{slug, title, color}` shape as resolveTaxonomyTerms().
     */
    static function getTaxonomyTerms(string $taxonomy): array
    {
        $parent = page('taxonomies/' . $taxonomy);
        if (!$parent) return [];

        return $parent->children()->listed()->map(fn($term) => self::getTaxonomyTermData($term))->values();
    }

    /**
     * Predicate keeping items tagged with any of the given term slugs on a
     * tags-based taxonomy field. Stored values are term UUIDs, so they are
     * resolved to slugs (cached per predicate) before comparison.
     */
    private static function taxonomyMatcher(string $fieldName, array $slugs): \Closure
    {
        $slugCache = [];

        return function ($item) use ($fieldName, $slugs, &$slugCache): bool {
            $itemSlugs = array_map(function (string $uuid) use (&$slugCache) {
                if (!array_key_exists($uuid, $slugCache)) {
                    $slugCache[$uuid] = page($uuid)?->slug();
                }
                return $slugCache[$uuid];
            }, $item->{$fieldName}()->split(','));

            return array_intersect($slugs, $itemSlugs) !== [];
        };
    }

    /**
     * Filters structure items on a tags-based taxonomy field, keeping items
     * tagged with any of the given term slugs. Stored values are term UUIDs,
     * so they are resolved to slugs before comparison. An empty $slugs returns
     * all items, so callers can pass through an optional filter directly.
     *
     * Usage:
     *   $faqs = page('faq')->faqs()->toStructure();                                     // all FAQs
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'domains', ['architecture']);   // one domain
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'domains', ['a', 'b']);         // any of multiple domains
     */
    static function filterStructureByTaxonomy(\Kirby\Cms\Structure $items, string $fieldName, array $slugs): \Kirby\Cms\Structure
    {
        if ($slugs === []) return $items;

        return $items->filter(self::taxonomyMatcher($fieldName, $slugs));
    }

    /**
     * Same as filterStructureByTaxonomy(), for a pages collection (events,
     * projects, ...). An empty $slugs returns all pages.
     */
    static function filterPagesByTaxonomy(\Kirby\Cms\Pages $pages, string $fieldName, array $slugs): \Kirby\Cms\Pages
    {
        if ($slugs === []) return $pages;

        return $pages->filter(self::taxonomyMatcher($fieldName, $slugs));
    }
}
