<?php

/**
 * Serialization of the `body` blocks fields to a JSON-ready list: the module
 * blockbuilder (`fields/body`, shared by the `page`, `team` and `job-offers`
 * templates) and the lighter event/project one (`fields/contentBody`).
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
            case 'module-text':
                return self::getTextBlockData($block);
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
            case 'module-resources':
                return self::getResourcesBlockData($block);
            case 'content-medias':
                return self::getContentMediasBlockData($block);
            case 'content-video':
                return self::getContentVideoBlockData($block);
            case 'content-text':
                return self::getContentTextBlockData($block);
            case 'content-links':
                return self::getContentLinksBlockData($block);
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

    private static function getTextBlockData(\Kirby\Cms\Block $block): array
    {
        // Field is named `content`, so it must be read through get() — $block->content() is the Content object.
        $text = $block->content()->get('content');

        return [
            'title'     => $block->title()->value(),
            'hideTitle' => $block->hideTitle()->toBool(),
            'content'   => $text->isNotEmpty() ? $text->value() : null,
            'ctas'      => self::resolveCtaStructures($block->ctas()),
            'variant'   => $block->variant()->or('default')->value(),
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
                'title'       => $row->title()->isNotEmpty() ? $row->title()->value() : null,
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
        // Field is named `content`, so it must be read through get() — $block->content() is the Content object.
        $text = $block->content()->get('content');

        return [
            'title'        => $block->title()->value(),
            'shortDesc'    => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'video'        => self::getVideo($block->content()->get('video')),
            'contentTitle' => $block->contentTitle()->isNotEmpty() ? $block->contentTitle()->value() : null,
            'content'      => $text->isNotEmpty() ? $text->value() : null,
            'variant'      => $block->variant()->or('default')->value(),
        ];
    }

    /**
     * The `fields/threeElements` structure, shared by the `module-infos-pratiques`
     * and `module-3-elements` blocks. Either empty or exactly 3 title/description
     * pairs (enforced by the blueprint); descriptions are required, titles may be
     * empty strings.
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
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    /** Questions shown inside the block; the CTA leads to the full, filtered list. */
    private const INFOS_PRATIQUES_FAQ_LIMIT = 3;

    private static function getInfosPratiquesBlockData(\Kirby\Cms\Block $block): array
    {
        $elements = self::getThreeElements($block->elements());

        $filters = [
            'sectors'  => array_column(self::resolveTaxonomyTerms($block->sectors(), 'sectors'), 'slug'),
            'programs' => array_column(self::resolveTaxonomyTerms($block->programs(), 'programs'), 'slug'),
            'publics'  => array_column(self::resolveTaxonomyTerms($block->publics(), 'publics'), 'slug'),
        ];

        $faqPage = site()->index()->template('faq')->first();
        $faqs = [];
        if ($faqPage) {
            foreach ($faqPage->sections()->toStructure() as $faqSection) {
                $items = $faqSection->faqs()->toStructure();
                foreach ($filters as $field => $slugs) {
                    $items = self::filterStructureByTaxonomy($items, $field, $slugs);
                }

                foreach ($items as $faq) {
                    $faqs[] = [
                        'question' => $faq->question()->value(),
                        'answer'   => $faq->answer()->value(),
                    ];

                    if (count($faqs) === self::INFOS_PRATIQUES_FAQ_LIMIT) break 2;
                }
            }
        }

        // The filters travel as `?sectors=a,b&programs=…&publics=…`, which the FAQ page reads to pre-select its own.
        $ctaUrl = null;
        if ($faqPage) {
            $query = [];
            foreach ($filters as $field => $slugs) {
                if ($slugs) $query[] = $field . '=' . implode(',', $slugs);
            }
            $ctaUrl = '/' . $faqPage->virtualPath() . ($query ? '?' . implode('&', $query) : '');
        }

        return [
            'title'    => $block->title()->value(),
            'subtitle' => $block->subtitle()->value(),
            'elements' => $elements,
            'faqs'     => $faqs,
            'cta'      => $ctaUrl ? [
                'label'  => $block->ctaLabel()->or('Plus de réponses')->value(),
                'url'    => $ctaUrl,
                'icon'   => 'arrow',
                'target' => null,
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
     * `recruitingSteps` are rendered by the same module. `shortDesc` is HTML limited
     * to `<a>` tags (see `getLinkedText()`), the frontend renders it with `@html`.
     */
    static function getTimelineSteps(\Kirby\Content\Field $field): array
    {
        return array_values($field->toStructure()->map(fn($step) => [
            'title'     => $step->title()->value(),
            'shortDesc' => self::getLinkedText($step->shortDesc()),
        ])->data());
    }

    private static function getAgendaBlockData(\Kirby\Cms\Block $block): array
    {
        $filters = [
            'programs' => array_column(self::resolveTaxonomyTerms($block->programs(), 'programs'), 'slug'),
            'publics'  => array_column(self::resolveTaxonomyTerms($block->publics(), 'publics'), 'slug'),
        ];

        $eventsPage = site()->index()->template('events')->first();
        $events = [];
        if ($eventsPage) {
            $children = $eventsPage->children()->listed();
            foreach ($filters as $field => $slugs) {
                $children = self::filterPagesByTaxonomy($children, $field, $slugs);
            }

            $children = self::splitEventsByDate($children)['upcoming'];

            $events = array_values($children->map(fn($event) => self::getEventCardData($event))->data());
        }

        $ctaUrl = $eventsPage ? '/' . $eventsPage->virtualPath() : null;

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'events'    => $events,
            'cta'       => $ctaUrl ? [
                'label'  => $block->ctaLabel()->or("Tout l'agenda")->value(),
                'url'    => $ctaUrl,
                'icon'   => 'arrow',
                'target' => null,
            ] : null,
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    private static function getProjetsBlockData(\Kirby\Cms\Block $block): array
    {
        $filters = [
            'programs'   => array_column(self::resolveTaxonomyTerms($block->programs(), 'programs'), 'slug'),
            'categories' => array_column(self::resolveTaxonomyTerms($block->categories(), 'categories'), 'slug'),
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
                'label'  => $block->ctaLabel()->or('Voir tous les projets')->value(),
                'url'    => $ctaUrl,
                'icon'   => 'arrow',
                'target' => null,
            ] : null,
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }

    private static function getResourcesBlockData(\Kirby\Cms\Block $block): array
    {
        $slugs = array_column(self::resolveTaxonomyTerms($block->programs(), 'programs'), 'slug');

        $resourcesPage = site()->index()->template('resources')->first();
        $resources = [];
        if ($resourcesPage) {
            $items = self::filterStructureByTaxonomy($resourcesPage->resources()->toStructure(), 'programs', $slugs);

            $resources = array_values($items->map(fn($item) => [
                'title'     => $item->title()->value(),
                'shortDesc' => $item->shortDesc()->value(),
                'image'     => self::getJsonEncodeImageDataOrNull($item->image()->toFile()),
            ])->data());
        }

        return [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'resources' => $resources,
            'variant'   => $block->variant()->or('default')->value(),
        ];
    }


    private static function getContentMediasBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'medias' => self::getJsonEncodeMediaArray($block->medias()->toFiles()),
        ];
    }

    private static function getContentVideoBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'video' => self::getVideo($block->content()->get('video')),
        ];
    }

    private static function getContentTextBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'title'       => $block->title()->value(),
            'description' => $block->description()->value(),
        ];
    }

    private static function getContentLinksBlockData(\Kirby\Cms\Block $block): array
    {
        return [
            'title' => $block->title()->isNotEmpty() ? $block->title()->value() : null,
            'links' => self::getExternalLinks($block->links()),
        ];
    }
}
