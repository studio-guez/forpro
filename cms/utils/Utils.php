<?php

/**
 * Shared serialization helpers for the `*.json.php` templates and the CMS
 * routes. The implementation is split by concern under `utils/traits/`; this
 * class only composes it, so callers keep the flat `Utils::` API.
 */

require_once __DIR__ . '/traits/UtilsMedia.php';
require_once __DIR__ . '/traits/UtilsLinks.php';
require_once __DIR__ . '/traits/UtilsTaxonomies.php';
require_once __DIR__ . '/traits/UtilsSeo.php';
require_once __DIR__ . '/traits/UtilsEmbeds.php';
require_once __DIR__ . '/traits/UtilsPages.php';
require_once __DIR__ . '/traits/UtilsBlocks.php';
require_once __DIR__ . '/traits/UtilsSearchText.php';
require_once __DIR__ . '/traits/UtilsSearch.php';

class Utils
{
    /** Images, videos and favicons -> JSON payloads. */
    use UtilsMedia;

    /** Link / CTA structures -> `{label, url}` payloads. */
    use UtilsLinks;

    /** Taxonomy terms: resolution, filtering, query strings. */
    use UtilsTaxonomies;

    /** Kirby SEO metadata -> frontend head payload. */
    use UtilsSeo;

    /** YouTube embeds. */
    use UtilsEmbeds;

    /** Page-level payloads: hero, event dates, cards, event/project base. */
    use UtilsPages;

    /** `body` blockbuilder modules -> JSON payloads. */
    use UtilsBlocks;

    /** Site search: indexing, scoring, excerpts. */
    use UtilsSearch;
}
