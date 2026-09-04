<?php

/**
 * `/llms.txt` — the Markdown index an LLM reads to find its way around the
 * site (https://llmstxt.org). Same page set as the sitemap, but a different
 * job: the sitemap is an inventory for a crawler, this is an orientation map
 * for a reader deciding what to fetch.
 *
 * That difference is why it lists the editorial pages and the index pages, and
 * not the collections behind them. A reader after a particular event, offer or
 * mission opens the index named here and finds it there, with the search and
 * filters the site already provides; reprinting those collections would spend
 * the reader's context restating the sitemap, and they grow without bound while
 * the pages that explain the site do not.
 *
 * The page set comes from `UtilsSeo::getIndexablePages()`, so the two documents
 * can never disagree about what the site publishes — and a `noindex`
 * environment (preprod) is left with headings and no links, exactly like its
 * empty sitemap.
 */
trait UtilsLlms
{
    /**
     * Headings of the file, in output order, mapped to the templates they
     * gather. `Optional` is the keyword the spec reserves for the pages a
     * reader may skip: here the legal ones.
     *
     * The index pages are gathered under one heading of their own rather than
     * scattered through `Pages`: they are the five entry points to everything
     * the site updates, and a reader should meet them as a group.
     */
    private const LLMS_SECTIONS = [
        'Pages' => [
            'templates' => ['page', 'team', 'press', 'factory-lab'],
        ],
        'Index et listes' => [
            'templates' => ['events', 'projects', 'job-offers', 'missions', 'faq'],
            // Read in the order they are declared above, which is the order
            // they matter in, not the alphabetical accident of their URLs.
            'sort'      => 'templates',
        ],
        'Optional' => [
            'templates' => ['impressum', 'basic-page'],
        ],
    ];

    /**
     * What a list page is, said in front of its own description: its title
     * never carries it — "Agenda" says nothing about listing every event, and
     * these are the pages a reader is most likely to want.
     *
     * Each claim is about the page's actual UI, so it has to be revisited when
     * a list page gains or loses its search field or its filters.
     */
    private const LLMS_ROLES = [
        'events'     => 'Page d’index : tous les événements du site, passés et à venir, avec recherche et filtres.',
        'projects'   => 'Page d’index : tous les projets du site, avec recherche et filtres.',
        'job-offers' => 'Page d’index : toutes les offres d’emploi ouvertes.',
        'missions'   => 'Page d’index : toutes les missions ouvertes, avec filtres.',
        'faq'        => 'Page d’index : toutes les questions fréquentes, avec recherche et filtres.',
    ];

    /** How long a link description may run before it is cut at a word. */
    private const LLMS_DESCRIPTION_LENGTH = 200;

    /** Same, for the blockquote under the title: a blurb, not the whole intro. */
    private const LLMS_SUMMARY_LENGTH = 300;

    static function getLlmsTxt(): string
    {
        $site = site();

        $blocks = ['# ' . $site->title()->value()];

        $paragraphs = self::getLlmsPresentation();

        // The blockquote is the one-line answer to "what is this site". The SEO
        // description is written for exactly that, so it wins; failing that the
        // presentation opens with it, and that opening paragraph is then spent
        // — it is the blurb, and is not repeated three lines lower.
        $summary = self::toPlainText($site->metaDescription()->value());

        if ($summary === '' && $paragraphs !== []) {
            $summary = array_shift($paragraphs);
        }

        if ($summary === '') {
            $summary = self::getLlmsHomeLead();
        }

        if ($summary !== '') {
            $blocks[] = '> ' . self::truncateAtWord($summary, self::LLMS_SUMMARY_LENGTH);
        }

        // Own block, so the editor's paragraphs stay paragraphs instead of
        // running into the facts underneath them.
        if ($paragraphs !== []) {
            $blocks[] = implode("\n\n", $paragraphs);
        }

        $blocks[] = implode("\n", self::getLlmsFacts());

        $pages = self::getIndexablePages();

        foreach (self::LLMS_SECTIONS as $heading => $section) {
            $listed = self::sortLlmsPages(
                $pages->filter(
                    fn(\Kirby\Cms\Page $page) => in_array($page->intendedTemplate()->name(), $section['templates'], true)
                ),
                $section
            );

            if ($listed === []) {
                continue;
            }

            $links = array_map(fn(\Kirby\Cms\Page $page) => self::getLlmsLink($page), $listed);

            $blocks[] = '## ' . $heading . "\n\n" . implode("\n", $links);
        }

        // Trailing newline: the file is read as text, and every line of it is a
        // statement, the last one included.
        return implode("\n\n", $blocks) . "\n";
    }

    /**
     * Reading order inside a section: the order the templates are declared in
     * when the section says so, the URL tree otherwise — which keeps a child
     * page under its parent. Ties break on the path, so the file is
     * byte-identical from one build to the next.
     *
     * @return \Kirby\Cms\Page[]
     */
    private static function sortLlmsPages(\Kirby\Cms\Pages $pages, array $section): array
    {
        $sorted = $pages->values();

        // What comes first: the declared position of a page's template, or —
        // by default — nothing except the home page, which is the site's entry
        // point and leads its section instead of being sorted into it.
        $rank = ($section['sort'] ?? null) === 'templates'
            ? fn(\Kirby\Cms\Page $page) => array_search($page->intendedTemplate()->name(), $section['templates'], true)
            : fn(\Kirby\Cms\Page $page) => $page->isHomePage() ? -1 : 0;

        usort(
            $sorted,
            fn(\Kirby\Cms\Page $a, \Kirby\Cms\Page $b) => ($rank($a) <=> $rank($b))
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

        // A list page states what it lists before whatever it says of itself.
        $role = self::LLMS_ROLES[$page->intendedTemplate()->name()] ?? '';

        $description = trim($role . ' ' . self::getLlmsDescription($page));

        return $description === '' ? $link : $link . ': ' . $description;
    }

    /**
     * What the page says about itself, in one line: its meta description when
     * an editor wrote one, its own lead text otherwise.
     */
    private static function getLlmsDescription(\Kirby\Cms\Page $page): string
    {
        // Ordered by how deliberate the text is: what an editor wrote for
        // search engines first, then the lead the page shows.
        $candidates = [
            $page->metadata()->get('metaDescription')->value(),
            $page->shortDesc()->value(),
            $page->intro()->value(),
        ];

        foreach ($candidates as $candidate) {
            if (($text = self::toPlainText($candidate)) !== '') {
                return self::truncateAtWord($text, self::LLMS_DESCRIPTION_LENGTH);
            }
        }

        return '';
    }

    /**
     * Last resort for the blurb: what the home page tells a visitor about the
     * site. Marketing copy aimed at someone who can already see the page, so it
     * only stands in when nothing written for a reader exists.
     */
    private static function getLlmsHomeLead(): string
    {
        $home = site()->homePage();

        if ($home === null) {
            return '';
        }

        foreach ([$home->metadata()->get('metaDescription')->value(), $home->intro()->value()] as $candidate) {
            if (($text = self::toPlainText($candidate)) !== '') {
                return $text;
            }
        }

        return '';
    }

    /**
     * The facts a reader needs before following any link: who the site belongs
     * to, how to reach them, and where the machine-readable index of the same
     * pages lives. All CMS fields; nothing here is inferred.
     *
     * @return string[]
     */
    private static function getLlmsFacts(): array
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
            'Événements, projets, offres d’emploi et missions ne sont pas listés un par un : '
                . 'ils vivent derrière les pages d’index ci-dessous, qui en donnent la liste à jour.',
            'Inventaire complet des URL : ' . $base . '/sitemap.xml',
        ]));
    }

    /**
     * The free presentation of the site, written in the Panel's SEO tab for
     * this file alone — nothing else on the site carries it, so an empty field
     * simply leaves the paragraph out rather than guessing one.
     *
     * Split into paragraphs, as the editor typed them, minus the heading
     * markers: a `#` line would open a section of its own and break the
     * document's outline.
     *
     * @return string[]
     */
    private static function getLlmsPresentation(): array
    {
        $lines = array_map(
            fn(string $line) => ltrim(trim($line), '#> '),
            explode("\n", (string)site()->llmsSummary()->value())
        );

        $paragraphs = preg_split('/\n{2,}/', trim(implode("\n", $lines))) ?: [];

        return array_values(array_filter(
            array_map(fn(string $paragraph) => trim($paragraph), $paragraphs),
            fn(string $paragraph) => $paragraph !== ''
        ));
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
