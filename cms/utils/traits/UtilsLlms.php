<?php

/**
 * `/llms.txt` — the Markdown index an LLM reads to find its way around the
 * site (https://llmstxt.org). Same idea as the sitemap, but written for a
 * reader rather than a crawler: a short description of the organization, then
 * one titled, described link per page, grouped by content type.
 *
 * It is built from the same page set as the sitemap
 * (`UtilsSeo::getIndexablePages()`), so the two documents can never disagree
 * about what the site publishes — and a `noindex` environment (preprod) is
 * left with headings and no links, exactly like its empty sitemap.
 */
trait UtilsLlms
{
    /**
     * Headings of the file, in output order, mapped to the templates they
     * gather. `Optional` is the keyword the spec reserves for the pages a
     * reader may skip: here the legal ones.
     */
    private const LLMS_SECTIONS = [
        'Pages'           => ['page', 'faq', 'team', 'press', 'factory-lab', 'events', 'projects', 'job-offers', 'missions'],
        'Événements'      => ['event'],
        'Projets'         => ['project'],
        "Offres d'emploi" => ['job-offer'],
        'Missions'        => ['mission'],
        'Optional'        => ['impressum', 'basic-page'],
    ];

    /** How long a link description may run before it is cut at a word. */
    private const LLMS_DESCRIPTION_LENGTH = 200;

    /** Same, for the blockquote under the title: a blurb, not the whole intro. */
    private const LLMS_SUMMARY_LENGTH = 300;

    static function getLlmsTxt(): string
    {
        $site = site();

        $blocks = ['# ' . $site->title()->value()];

        if ($summary = self::getLlmsSummary()) {
            $blocks[] = '> ' . $summary;
        }

        $blocks[] = implode("\n", self::getLlmsDetails());

        $pages = self::getIndexablePages();

        foreach (self::LLMS_SECTIONS as $heading => $templates) {
            $section = $pages->filter(
                fn(\Kirby\Cms\Page $page) => in_array($page->intendedTemplate()->name(), $templates, true)
            );

            if ($section->count() === 0) {
                continue;
            }

            $links = array_map(
                fn(\Kirby\Cms\Page $page) => self::getLlmsLink($page),
                self::sortLlmsPages($section, $heading)
            );

            $blocks[] = '## ' . $heading . "\n\n" . implode("\n", $links);
        }

        // Trailing newline: the file is read as text, and every line of it is a
        // statement, the last one included.
        return implode("\n\n", $blocks) . "\n";
    }

    /**
     * Reading order inside a section. Events lead with what is still to come,
     * because that is what a reader asking about the site wants first;
     * everything else follows the URL tree, so children read under their parent.
     *
     * @return \Kirby\Cms\Page[]
     */
    private static function sortLlmsPages(\Kirby\Cms\Pages $pages, string $heading): array
    {
        if ($heading === 'Événements') {
            ['upcoming' => $upcoming, 'past' => $past] = self::splitEventsByDate($pages);

            return [...$upcoming->values(), ...$past->values()];
        }

        $sorted = $pages->values();

        // The home page is the entry point, so it leads its section instead of
        // being sorted into it.
        usort(
            $sorted,
            fn(\Kirby\Cms\Page $a, \Kirby\Cms\Page $b) => ($b->isHomePage() <=> $a->isHomePage())
                ?: strcmp($a->virtualPath(), $b->virtualPath())
        );

        return $sorted;
    }

    /** One `- [Title](url): description` entry. */
    private static function getLlmsLink(\Kirby\Cms\Page $page): string
    {
        // Brackets in a title would otherwise cut the Markdown link short.
        $title = str_replace(['[', ']'], ['\[', '\]'], $page->title()->value());

        $link = '- [' . $title . '](' . $page->frontendUrl() . ')';

        $description = self::getLlmsDescription($page);

        return $description === '' ? $link : $link . ': ' . $description;
    }

    /**
     * What the page says about itself, in one line: its meta description when
     * an editor wrote one, its own lead text otherwise.
     */
    private static function getLlmsDescription(\Kirby\Cms\Page $page): string
    {
        // Ordered by how deliberate the text is: what an editor wrote for
        // search engines first, then the lead the page shows, then — for a job
        // offer, which has neither — the opening of the offer itself.
        $candidates = [
            $page->metadata()->get('metaDescription')->value(),
            $page->shortDesc()->value(),
            $page->intro()->value(),
            $page->description()->value(),
        ];

        foreach ($candidates as $candidate) {
            if (($text = self::toPlainText($candidate)) !== '') {
                return self::truncateAtWord($text, self::LLMS_DESCRIPTION_LENGTH);
            }
        }

        return '';
    }

    /** The site blurb: the site's own meta description, or the home page lead. */
    private static function getLlmsSummary(): string
    {
        $site = site();
        $home = $site->homePage();

        $candidates = [$site->metaDescription()->value()];

        if ($home !== null) {
            $candidates[] = $home->metadata()->get('metaDescription')->value();
            $candidates[] = $home->intro()->value();
        }

        foreach ($candidates as $candidate) {
            if (($text = self::toPlainText($candidate)) !== '') {
                return self::truncateAtWord($text, self::LLMS_SUMMARY_LENGTH);
            }
        }

        return '';
    }

    /**
     * The facts a reader needs before following any link, all of them CMS
     * fields: who the site belongs to, how to reach them, and where the
     * machine-readable index of the same pages lives.
     *
     * @return string[]
     */
    private static function getLlmsDetails(): array
    {
        $site = site();
        $base = rtrim(option('url_frontend', $site->url()), '/');

        $address = implode(', ', array_filter([
            $site->addressName()->value(),
            $site->addressStreet()->value(),
            trim($site->addressPostalCode()->value() . ' ' . $site->addressLocality()->value()),
            $site->addressRegion()->value(),
            $site->addressCountry()->value(),
        ]));

        $contact = implode(', ', array_filter([
            $site->contactEmail()->value(),
            $site->contactPhone()->value(),
        ]));

        return array_values(array_filter([
            $address === '' ? null : $address . '.',
            $contact === '' ? null : 'Contact : ' . $contact . '.',
            'Site en français. Chaque lien ci-dessous mène à une page publique du site.',
            'Plan du site : ' . $base . '/sitemap.xml',
        ]));
    }

    /** Cuts at the last whole word before `$length`, and marks the cut. */
    private static function truncateAtWord(string $text, int $length): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $cut = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($cut, ' ');

        return rtrim($lastSpace === false ? $cut : mb_substr($cut, 0, $lastSpace), " \t,.;:!?-—") . '…';
    }
}
