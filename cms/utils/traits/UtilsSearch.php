<?php

/**
 * Site search: which pages are indexed, how hits are scored, paginated and
 * serialized. Text extraction/normalization lives in UtilsSearchText.
 */
trait UtilsSearch
{
    use UtilsSearchText;

    /**
     * Templates that have a route on the decoupled frontend (i.e. whose pages
     * can be linked to from a search result), mapped to their public label.
     * Taxonomy terms and the `pages` container are structural only.
     */
    private const SEARCHABLE_TEMPLATES = [
        'page'     => 'Page',
        'faq'      => 'Page',
        'event'    => 'Événement',
        'project'  => 'Projet',
        'team'     => 'Page',
    ];

    /** Result groups the frontend tabs filter on; every other template is a page. */
    private const SEARCH_GROUPS = [
        'event'   => 'events',
        'project' => 'projects',
    ];

    /**
     * Every page the frontend can link to, i.e. a potential search result.
     */
    static function getSearchablePages(): \Kirby\Cms\Pages
    {
        return site()->index()->filter(
            fn(\Kirby\Cms\Page $page) => isset(self::SEARCHABLE_TEMPLATES[$page->intendedTemplate()->name()])
        );
    }

    /**
     * Full-text search across every page with a public URL. All words of the
     * query must be found; matches in the title outrank matches in the intro,
     * which outrank matches in the body. `counts` always covers every group so
     * the frontend tabs keep their totals while a single group is displayed.
     *
     * @return array{query: string, group: string, offset: int, counts: array<string, int>, total: int, hasMore: bool, results: array<int, array>}
     */
    static function searchPages(string $query, string $group = 'all', int $offset = 0, int $limit = 10): array
    {
        $query = trim(preg_replace('/\s+/u', ' ', $query) ?? '');
        $normalizedQuery = self::normalizeForSearch($query);
        $words = array_values(array_unique(array_filter(
            explode(' ', $normalizedQuery),
            fn(string $word) => mb_strlen($word) >= 2
        )));

        $counts = ['all' => 0, 'pages' => 0, 'events' => 0, 'projects' => 0];

        if ($words === []) {
            return [
                'query'   => $query,
                'group'   => $group,
                'offset'  => $offset,
                'counts'  => $counts,
                'total'   => 0,
                'hasMore' => false,
                'results' => [],
            ];
        }

        $matches = [];

        foreach (self::getSearchablePages() as $page) {
            ['title' => $title, 'lead' => $lead, 'body' => $body] = self::getSearchHaystacks($page);

            $normalizedTitle = self::normalizeForSearch($title);
            $normalizedLead = self::normalizeForSearch($lead);
            $content = trim($lead . ' ' . $body);
            $normalizedContent = self::normalizeForSearch($content);

            $score = 0;
            foreach ($words as $word) {
                if (str_contains($normalizedTitle, $word)) {
                    $score += 10;
                } elseif (str_contains($normalizedLead, $word)) {
                    $score += 4;
                } elseif (str_contains($normalizedContent, $word)) {
                    $score += 1;
                } else {
                    continue 2;
                }
            }

            // Whole-query matches rank above pages that only match word by word.
            if ($normalizedTitle === $normalizedQuery) {
                $score += 50;
            } elseif (str_contains($normalizedTitle, $normalizedQuery)) {
                $score += 20;
            } elseif (str_contains($normalizedContent, $normalizedQuery)) {
                $score += 5;
            }

            $template = $page->intendedTemplate()->name();
            $pageGroup = self::SEARCH_GROUPS[$template] ?? 'pages';
            $counts['all']++;
            $counts[$pageGroup]++;

            $matches[] = [
                'score' => $score,
                'group' => $pageGroup,
                'page'  => $page,
                'result' => [
                    'id'         => $page->id(),
                    'title'      => $page->title()->value(),
                    'url'        => $page->isHomePage() ? '/' : '/' . $page->virtualPath(),
                    'type'       => $template,
                    'typeLabel'  => self::SEARCHABLE_TEMPLATES[$template],
                    'group'      => $pageGroup,
                    'excerpt'    => self::buildSearchExcerpt($content, $normalizedContent, $words),
                ],
            ];
        }

        if ($group !== 'all') {
            $matches = array_values(array_filter($matches, fn(array $match) => $match['group'] === $group));
        }

        usort($matches, fn(array $a, array $b) => $b['score'] <=> $a['score']
            ?: strcmp($a['result']['title'], $b['result']['title']));

        $total = count($matches);
        $offset = max(0, $offset);
        $slice = array_slice($matches, $offset, $limit);

        return [
            'query'   => $query,
            'group'   => $group,
            'offset'  => $offset,
            'counts'  => $counts,
            'total'   => $total,
            'hasMore' => $offset + count($slice) < $total,
            // Thumbnails are only generated for the returned slice, not the whole index.
            'results' => array_map(
                fn(array $match) => $match['result'] + ['cover' => self::getSearchCover($match['page'])],
                $slice
            ),
        ];
    }

    /**
     * Square thumbnail of a result's cover, in the shared image payload shape.
     * Deliberately lighter than `getJsonEncodeImageData()`: a result row only
     * needs the crop and its retina variant, never the full srcset.
     */
    private static function getSearchCover(\Kirby\Cms\Page $page): ?array
    {
        $file = $page->cover()->toFile();

        if ($file === null || $file->type() !== 'image') {
            return null;
        }

        return self::getImageData($file, [
            // The crop already honours the file's focus point.
            'focus'       => null,
            'width'       => 240,
            'height'      => 240,
            'url'         => $file->crop(240, 240)->url(),
            'srcset'      => $file->crop(240, 240)->url() . ' 240w, ' . $file->crop(480, 480)->url() . ' 480w',
        ]);
    }
}
