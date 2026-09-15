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
     * `{slug, title, color}` shape as resolveTaxonomyTerms(), each with its
     * sub-terms under `children` (empty for single-level taxonomies).
     */
    static function getTaxonomyTerms(string $taxonomy): array
    {
        $parent = page('taxonomies/' . $taxonomy);
        if (!$parent) return [];

        return $parent->children()->listed()->map(fn($term) => [
            ...self::getTaxonomyTermData($term),
            'children' => $term->children()->listed()->values(fn($child) => self::getTaxonomyTermData($child)),
        ])->values();
    }

    /**
     * Every term slug a pages collection actually uses on a tags-based
     * taxonomy field, in the order the terms are first met. Lets a frontend
     * only offer filters that lead somewhere (`filterUsedTerms()`) without
     * having to ship the whole collection.
     */
    static function getUsedTaxonomySlugs(\Kirby\Cms\Pages $pages, string $fieldName): array
    {
        $slugs = [];

        foreach ($pages as $page) {
            foreach ($page->{$fieldName}()->split(',') as $uuid) {
                if ($slug = page($uuid)?->slug()) {
                    $slugs[$slug] = true;
                }
            }
        }

        return array_keys($slugs);
    }

    /**
     * Adds the sub-terms of every selected parent term, so an item tagged only
     * with a sub-term still matches a filter on its parent.
     *
     * Deliberately permissive: this backs the CMS-authored filters (blocks, the
     * FAQ), where a field may legitimately name a sub-term on its own. The
     * stricter rule the index URLs need is `resolveTaxonomySelection()`.
     */
    private static function expandTaxonomySlugs(string $taxonomy, array $slugs): array
    {
        $parent = page('taxonomies/' . $taxonomy);
        if (!$parent) return $slugs;

        foreach ($slugs as $slug) {
            if ($term = $parent->children()->listed()->findBy('slug', $slug)) {
                $slugs = array_merge($slugs, $term->children()->listed()->values(fn($child) => $child->slug()));
            }
        }

        return array_values(array_unique($slugs));
    }

    /**
     * Turns a raw taxonomy selection — as it arrives in a `?publics=a,b` URL —
     * into the slugs an item may be tagged with to match it.
     *
     * This is the exact mirror of `keepKnownSlugs()` + `expandSelection()` in
     * `website/src/lib/utils/filters.ts`, and has to stay one, because both ends
     * resolve the same URL: the CMS to render the first page, the browser to
     * filter what it still holds client-side.
     *
     * - a sub-term only counts while its parent is selected, since that is the
     *   only state in which the filter UI offers it;
     * - a selected parent stands for all of its sub-terms, *unless* some of them
     *   are selected too, in which case the selection narrows to those.
     *
     * That narrowing is why the raw selection has to travel: an already-expanded
     * list cannot say whether `[parent, childA]` meant "just childA" or "the
     * whole parent", and expanding it again would widen it back.
     *
     * Slugs that are not terms of this taxonomy are dropped, so a stale URL
     * degrades to a wider result rather than an empty one. Returns `[]` for a
     * selection that resolves to nothing, which the filters read as "no filter".
     */
    static function resolveTaxonomySelection(string $taxonomy, array $selected): array
    {
        $parent = page('taxonomies/' . $taxonomy);
        if (!$parent) return $selected;

        $terms = $parent->children()->listed();

        $kept = array_values(array_filter($selected, function (string $slug) use ($terms, $selected): bool {
            if ($terms->findBy('slug', $slug)) return true;

            foreach ($terms as $term) {
                if ($term->children()->listed()->findBy('slug', $slug)) {
                    return in_array($term->slug(), $selected, true);
                }
            }

            return false;
        }));

        $slugs = [];
        foreach ($kept as $slug) {
            $slugs[$slug] = true;

            $term = $terms->findBy('slug', $slug);
            if (!$term) continue;

            $children = $term->children()->listed()->values(fn($child) => $child->slug());
            $narrowed = array_values(array_intersect($children, $kept));

            foreach ($narrowed !== [] ? $narrowed : $children as $child) {
                $slugs[$child] = true;
            }
        }

        return array_keys($slugs);
    }

    /**
     * Predicate keeping items tagged with any of the given term slugs on a
     * tags-based taxonomy field. Stored values are term UUIDs, so they are
     * resolved to slugs (cached per predicate) before comparison.
     *
     * `$expand` off means the slugs are matched as given, for a caller that
     * already resolved the selection with `resolveTaxonomySelection()`:
     * expanding again would widen a narrowed parent back to every sub-term.
     */
    private static function taxonomyMatcher(string $fieldName, array $slugs, bool $expand = true): \Closure
    {
        // On every call site the field name is also the taxonomy name.
        $slugs = $expand ? self::expandTaxonomySlugs($fieldName, $slugs) : $slugs;
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
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'programs', ['explore']);       // one program
     *   $faqs = Utils::filterStructureByTaxonomy($faqs, 'programs', ['a', 'b']);        // any of multiple programs
     */
    static function filterStructureByTaxonomy(\Kirby\Cms\Structure $items, string $fieldName, array $slugs): \Kirby\Cms\Structure
    {
        if ($slugs === []) return $items;

        return $items->filter(self::taxonomyMatcher($fieldName, $slugs));
    }

    /**
     * Same as filterStructureByTaxonomy(), for a pages collection (events,
     * projects, ...). An empty $slugs returns all pages.
     *
     * Pass `$expand = false` for a selection already resolved by
     * `resolveTaxonomySelection()`, as the paginated index lists do.
     */
    static function filterPagesByTaxonomy(\Kirby\Cms\Pages $pages, string $fieldName, array $slugs, bool $expand = true): \Kirby\Cms\Pages
    {
        if ($slugs === []) return $pages;

        return $pages->filter(self::taxonomyMatcher($fieldName, $slugs, $expand));
    }
}
