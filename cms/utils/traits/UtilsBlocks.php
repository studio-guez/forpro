<?php

/**
 * Serialization of the `body` blocks field (the module blockbuilder shared by
 * the `page`, `team` and `job-offers` templates) to a JSON-ready list.
 */
trait UtilsBlocks
{
    /**
     * Resolves a blocks field to `[{id, type, isHidden, content}]`.
     * Unknown block types fall back to their raw content array.
     */
    static function getBodyBlocks(\Kirby\Content\Field $field): array
    {
        $blocks = [];
        foreach ($field->toBlocks() as $block) {
            $blocks[] = [
                'id'       => $block->id(),
                'type'     => $block->type(),
                'isHidden' => $block->isHidden(),
                'content'  => self::getBlockContent($block),
            ];
        }

        return $blocks;
    }

    private static function getBlockContent(\Kirby\Cms\Block $block): array
    {
        switch ($block->type()) {
            case 'module-titre-texte-image':
                return self::getTitreTexteImageBlockData($block);
            case 'module-cta':
                return self::getCtaModuleData($block);
            case 'module-partenaires':
                return self::getPartenairesBlockData($block);
            case 'module-cases':
                return self::getCasesBlockData($block);
            case 'module-grille-images':
                return self::getGrilleImagesBlockData($block);
            case 'module-video':
                return self::getVideoBlockData($block);
            case 'module-infos-pratiques':
                return self::getInfosPratiquesBlockData($block);
            case 'module-3-elements':
                return self::getThreeElementsBlockData($block);
            case 'module-timeline':
                return self::getTimelineBlockData($block);
            case 'module-agenda':
                return self::getAgendaBlockData($block);
            case 'module-projets':
                return self::getProjetsBlockData($block);
            default:
                return $block->toArray()['content'] ?? [];
        }
    }

    private static function getTitreTexteImageBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'title'         => $block->title()->value(),
            'description'   => $block->description()->value(),
            'image'         => self::getJsonEncodeImageDataOrNull($block->image()->toFile()),
            'imagePosition' => $block->content()->get('imagePosition')->or('right')->value(),
            'variant'       => $block->variant()->or('default')->value(),
            'cta'           => self::resolveCtaStructure($block->cta()),
        ];
    }

    private static function getPartenairesBlockData(\Kirby\Cms\Block $block): array
    {
        $partners = [];
        foreach ($block->partners()->toStructure() as $partner) {
            $logoFile = $partner->logo()->toFile();
            if (!$logoFile) continue;
            $partners[] = [
                'logo'  => self::getJsonEncodeImageData($logoFile),
                'url'   => $partner->url()->isNotEmpty() ? $partner->url()->value() : null,
                // The logo alt is the accessible name; fall back to the link target.
                'label' => $logoFile->alt()->isNotEmpty()
                    ? $logoFile->alt()->value()
                    : $partner->url()->value(),
            ];
        }

        return [
            'title'    => $block->title()->value(),
            'subtitle' => $block->subtitle()->isNotEmpty() ? $block->subtitle()->value() : null,
            'partners' => $partners,
            'variant'  => $block->variant()->or('default')->value(),
        ];
    }

    private static function getCasesBlockData(\Kirby\Cms\Block $block): array
    {
        $rows = [];
        foreach ($block->rows()->toStructure() as $row) {
            $rows[] = [
                'title'       => $row->title()->value(),
                'hideTitle'   => $row->hideTitle()->toBool(),
                'description' => $row->description()->value(),
                'cta'         => self::resolveCtaStructure($row->cta()),
                'media'       => self::getJsonEncodeMediaArray($row->media()->toFiles()),
            ];
        }

        return [
            'title'     => $block->title()->value(),
            'hideTitle' => $block->hideTitle()->toBool(),
            'intro'     => $block->intro()->value(),
            'rows'      => $rows,
            'cta'       => self::resolveCtaStructure($block->cta()),
            'layout'    => $block->layout()->or('alternate')->value(),
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    private static function getGrilleImagesBlockData(\Kirby\Cms\Block $block): array
    {
        $images = [];
        foreach ($block->images()->toStructure() as $item) {
            $imageFile = $item->image()->toFile();
            if (!$imageFile) continue;
            $images[] = [
                'image' => self::getJsonEncodeImageData($imageFile),
                'title' => $item->title()->value(),
            ];
        }

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'images'    => $images,
            'cta'       => self::resolveCtaStructure($block->cta()),
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    private static function getVideoBlockData(\Kirby\Cms\Block $block): array
    {
        $video = $block->content()->get('video')->toFile();
        // Field is named `content`, so it must be read through get() — $block->content() is the Content object.
        $text  = $block->content()->get('content');

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'video'     => $video ? self::getJsonEncodeMediaData($video) : null,
            'content'   => $text->isNotEmpty() ? $text->value() : null,
        ];
    }

    /**
     * The `fields/threeElements` structure, shared by the `module-infos-pratiques`
     * and `module-3-elements` blocks. Either empty or exactly 3 title/description
     * pairs (enforced by the blueprint).
     */
    static function getThreeElements(\Kirby\Content\Field $field): array
    {
        $elements = [];
        $row = $field->toStructure()->first();
        if ($row) {
            foreach ([1, 2, 3] as $i) {
                $elements[] = [
                    'title'       => $row->{'title' . $i}()->value(),
                    'description' => $row->{'description' . $i}()->value(),
                ];
            }
        }

        return $elements;
    }

    private static function getThreeElementsBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'elements'  => self::getThreeElements($block->elements()),
        ];
    }

    private static function getInfosPratiquesBlockData(\Kirby\Cms\Block $block): array
    {
        $elements = self::getThreeElements($block->elements());

        $categorySlugs = array_column(self::resolveTaxonomyTerms($block->faqCategories(), 'faq-categories'), 'slug');

        // All matching FAQ questions, pulled from the FAQ page.
        $faqPage = site()->index()->template('faq')->first();
        $faqs = [];
        if ($faqPage) {
            foreach ($faqPage->sections()->toStructure() as $faqSection) {
                foreach (self::filterStructureByTaxonomy($faqSection->faqs()->toStructure(), 'faqCategories', $categorySlugs) as $faq) {
                    $faqs[] = [
                        'question' => $faq->question()->value(),
                        'answer'   => $faq->answer()->value(),
                    ];
                }
            }
        }

        // The CTA URL is resolved here so a FAQ slug change never breaks the frontend link.
        $ctaUrl = $faqPage ? '/' . $faqPage->virtualPath() : null;

        return [
            'title'    => $block->title()->value(),
            'subtitle' => $block->subtitle()->value(),
            'elements' => $elements,
            'faqs'     => $faqs,
            'cta'      => $ctaUrl ? [
                'label' => $block->ctaLabel()->or('Plus de réponses')->value(),
                'url'   => $ctaUrl,
                'icon'  => 'arrow',
            ] : null,
            'variant'  => $block->variant()->or('default')->value(),
        ];
    }

    private static function getTimelineBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'title'     => $block->title()->value(),
            'hideTitle' => $block->hideTitle()->toBool(),
            'steps'     => self::getTimelineSteps($block->steps()),
        ];
    }

    /**
     * Steps of a timeline. Shared with the `job-offer` template, whose
     * `recruitingSteps` are rendered by the same module.
     */
    static function getTimelineSteps(\Kirby\Content\Field $field): array
    {
        return array_values($field->toStructure()->map(fn($step) => [
            'title'     => $step->title()->value(),
            'shortDesc' => $step->shortDesc()->value(),
        ])->data());
    }

    private static function getAgendaBlockData(\Kirby\Cms\Block $block): array
    {
        $filters = [
            'domains'     => array_column(self::resolveTaxonomyTerms($block->domains(), 'domains'), 'slug'),
            'eventThemes' => array_column(self::resolveTaxonomyTerms($block->eventThemes(), 'event-themes'), 'slug'),
        ];

        $eventsPage = site()->index()->template('events')->first();
        $events = [];
        if ($eventsPage) {
            $children = $eventsPage->children()->listed();
            foreach ($filters as $field => $slugs) {
                $children = self::filterPagesByTaxonomy($children, $field, $slugs);
            }

            // Upcoming events only (an event stays listed until it is over), soonest first.
            $children = self::splitEventsByDate($children)['upcoming'];

            $events = array_values($children->map(fn($event) => self::getEventCardData($event))->data());
        }

        // Resolved here so an agenda slug change never breaks the frontend link.
        $ctaUrl = $eventsPage ? '/' . $eventsPage->virtualPath() : null;

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'events'    => $events,
            'cta'       => $ctaUrl ? [
                'label' => $block->ctaLabel()->or("Tout l'agenda")->value(),
                'url'   => $ctaUrl,
                'icon'  => 'arrow',
            ] : null,
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    private static function getProjetsBlockData(\Kirby\Cms\Block $block): array
    {
        $filters = [
            'projectThemes' => array_column(self::resolveTaxonomyTerms($block->projectThemes(), 'project-themes'), 'slug'),
            'projectTypes'  => array_column(self::resolveTaxonomyTerms($block->projectTypes(), 'project-types'), 'slug'),
        ];

        $projectsPage = site()->index()->template('projects')->first();
        $projects = [];
        if ($projectsPage) {
            $children = $projectsPage->children()->listed();
            foreach ($filters as $field => $slugs) {
                $children = self::filterPagesByTaxonomy($children, $field, $slugs);
            }

            $projects = array_values($children->map(fn($project) => self::getProjectCardData($project))->data());
        }

        $ctaUrl = $projectsPage ? '/' . $projectsPage->virtualPath() : null;

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'projects'  => $projects,
            'cta'       => $ctaUrl ? [
                'label' => $block->ctaLabel()->or('Voir tous les projets')->value(),
                'url'   => $ctaUrl,
                'icon'  => 'arrow',
            ] : null,
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }
}
