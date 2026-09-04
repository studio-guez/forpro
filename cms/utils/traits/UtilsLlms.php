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
     * Headings of the file, in output order. `templates` are the pages the
     * section lists; `index` is the template of the page that lists those same
     * pages on the site. `Optional` is the keyword the spec reserves for the
     * pages a reader may skip: here the legal ones.
     *
     * The index page leads its own section rather than sitting in `Pages`: it
     * is the entry point of that content type — the page to follow to browse,
     * search and paginate what the flat list below only samples — so it has to
     * be the first link met under the heading, and a link, not a sentence: a
     * reader collecting the list items would miss anything else.
     */
    private const LLMS_SECTIONS = [
        'Pages'           => ['templates' => ['page', 'faq', 'team', 'press', 'factory-lab']],
        'Événements'      => ['templates' => ['event'],     'index' => 'events'],
        'Projets'         => ['templates' => ['project'],   'index' => 'projects'],
        "Offres d'emploi" => ['templates' => ['job-offer'], 'index' => 'job-offers'],
        'Missions'        => ['templates' => ['mission'],   'index' => 'missions'],
        'Optional'        => ['templates' => ['impressum', 'basic-page']],
    ];

    /**
     * What a list page is, said in front of its own description: its title
     * never carries it — "Agenda" says nothing about listing every event, and
     * these pages are the entry points a reader is most likely to want.
     *
     * Each claim is about the page's actual UI, so it has to be revisited when
     * a list page gains or loses its search field or its filters.
     */
    private const LLMS_ROLES = [
        'events'     => 'Page d’index : tous les événements du site, avec recherche et filtres.',
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
            $listed = $pages->filter(
                fn(\Kirby\Cms\Page $page) => in_array($page->intendedTemplate()->name(), $section['templates'], true)
            );

            $indexPage = self::findLlmsIndexPage($pages, $section['index'] ?? null);

            if ($listed->count() === 0 && $indexPage === null) {
                continue;
            }

            $links = array_map(
                fn(\Kirby\Cms\Page $page) => self::getLlmsLink($page),
                self::sortLlmsPages($listed, $heading)
            );

            if ($indexPage !== null) {
                array_unshift($links, self::getLlmsLink($indexPage));
            }

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

    /** The section's own index page on the site, when it is published. */
    private static function findLlmsIndexPage(\Kirby\Cms\Pages $pages, ?string $template): ?\Kirby\Cms\Page
    {
        if ($template === null) {
            return null;
        }

        return $pages->filter(
            fn(\Kirby\Cms\Page $page) => $page->intendedTemplate()->name() === $template
        )->first();
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
            'Plan du site : ' . $base . '/sitemap.xml',
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
