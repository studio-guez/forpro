<?php

/**
 * Text extraction and normalization behind the site search: what of a page is
 * indexed, and how the raw text is folded/cut for matching and excerpts.
 */
trait UtilsSearchText
{
    /** Field types whose raw value is human-readable text. */
    private const SEARCH_TEXT_TYPES = ['text', 'textarea', 'writer', 'markdown', 'list', 'tags'];

    /** Already covered by the title/lead buckets, skipped when collecting the body. */
    private const SEARCH_SKIPPED_FIELDS = ['title', 'overtitle', 'subtitle', 'introtitle', 'intro', 'shortdesc'];

    /**
     * Nested keys (inside blocks/structures) whose value is human-readable text.
     * Everything else there is machine data (uuids, colors, layout toggles, ...).
     */
    private const SEARCH_TEXT_KEYS = [
        'title',
        'subtitle',
        'overtitle',
        'description',
        'intro',
        'introtitle',
        'shortdesc',
        'question',
        'answer',
        'label',
        'text',
        'content',
        'caption',
        'collectivename',
    ];

    /**
     * Accent folding table. Every replacement is one character long, so offsets
     * in the normalized string stay aligned with the original one.
     */
    private const SEARCH_ACCENT_MAP = [
        'À' => 'a',
        'Á' => 'a',
        'Â' => 'a',
        'Ã' => 'a',
        'Ä' => 'a',
        'Å' => 'a',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'Ç' => 'c',
        'ç' => 'c',
        'È' => 'e',
        'É' => 'e',
        'Ê' => 'e',
        'Ë' => 'e',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'Ì' => 'i',
        'Í' => 'i',
        'Î' => 'i',
        'Ï' => 'i',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'Ñ' => 'n',
        'ñ' => 'n',
        'Ò' => 'o',
        'Ó' => 'o',
        'Ô' => 'o',
        'Õ' => 'o',
        'Ö' => 'o',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'Ù' => 'u',
        'Ú' => 'u',
        'Û' => 'u',
        'Ü' => 'u',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'Ý' => 'y',
        'ý' => 'y',
        'ÿ' => 'y',
        '’' => "'",
        '‘' => "'",
        '–' => '-',
        '—' => '-',
    ];

    /**
     * Splits a page's indexable text into the three ranking buckets.
     * The body is driven by the blueprint field types, so a new text field or
     * block is indexed without touching this helper.
     *
     * @return array{title: string, lead: string, body: string}
     */
    private static function getSearchHaystacks(\Kirby\Cms\Page $page): array
    {
        $title = self::joinSearchParts([
            $page->title()->value(),
            $page->overtitle()->value(),
            $page->subtitle()->value(),
        ]);

        $lead = self::joinSearchParts([
            $page->introTitle()->value(),
            $page->intro()->value(),
            $page->shortDesc()->value(),
        ]);

        $body = [];
        foreach ($page->blueprint()->fields() as $name => $blueprint) {
            $type = $blueprint['type'] ?? '';
            if (in_array(strtolower((string)$name), self::SEARCH_SKIPPED_FIELDS, true)) {
                continue;
            }

            $field = $page->content()->get($name);
            if ($field->isEmpty()) {
                continue;
            }

            if ($type === 'blocks') {
                foreach ($field->toBlocks() as $block) {
                    self::collectSearchText($block->content()->toArray(), $body);
                }
            } elseif ($type === 'structure') {
                foreach ($field->toStructure() as $item) {
                    self::collectSearchText($item->content()->toArray(), $body);
                }
            } elseif (in_array($type, self::SEARCH_TEXT_TYPES, true)) {
                $body[] = $field->value();
            }
        }

        return ['title' => $title, 'lead' => $lead, 'body' => self::joinSearchParts($body)];
    }

    /**
     * Recursively pulls the human-readable strings out of a block/structure
     * content array. Numeric keys only carry nested rows, never text.
     */
    private static function collectSearchText(array $data, array &$parts): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::collectSearchText($value, $parts);
            } elseif (is_string($value) && $value !== '' && in_array(strtolower((string)$key), self::SEARCH_TEXT_KEYS, true)) {
                $parts[] = $value;
            }
        }
    }

    /**
     * Strips markup out of the given values and joins them into one haystack.
     * UUID references (taxonomy tags, page links) are swapped for the target
     * title, and values without a single letter (times, dates, ids) dropped.
     */
    private static function joinSearchParts(array $parts): string
    {
        $text = implode(' ', array_filter(
            $parts,
            fn($part) => is_string($part) && preg_match('/\p{L}/u', $part) === 1
        ));

        $text = preg_replace_callback(
            '#\b(?:page|file|site|user)://[a-zA-Z0-9._-]+#',
            fn(array $match) => page($match[0])?->title()->value() ?? '',
            $text
        ) ?? $text;

        $text = html_entity_decode(preg_replace('/<[^>]*+>/', ' ', $text) ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    }

    /**
     * Lowercased, accent-folded copy of the text. Every replacement is one
     * character long, so offsets stay aligned with the original string and can
     * be reused to cut an excerpt out of it.
     */
    private static function normalizeForSearch(string $text): string
    {
        return mb_strtolower(strtr($text, self::SEARCH_ACCENT_MAP), 'UTF-8');
    }

    /**
     * Text snippet around the first matching word, cut on word boundaries.
     */
    private static function buildSearchExcerpt(string $text, string $normalized, array $words, int $length = 180): string
    {
        if ($text === '') {
            return '';
        }

        $position = 0;
        foreach ($words as $word) {
            $found = mb_strpos($normalized, $word);
            if ($found !== false) {
                $position = $found;
                break;
            }
        }

        $start = max(0, $position - 60);
        $excerpt = mb_substr($text, $start, $length);

        if ($start > 0) {
            // Drop the partial first word left by the cut.
            $excerpt = '…' . ltrim(mb_substr($excerpt, (int)mb_strpos($excerpt, ' ')));
        }
        if (mb_strlen($text) > $start + $length) {
            $excerpt = rtrim(mb_substr($excerpt, 0, (int)mb_strrpos($excerpt, ' ') ?: null)) . '…';
        }

        return $excerpt;
    }
}
