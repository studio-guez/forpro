<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['template'] = 'page';
$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();

$json['overtitle'] = $page->overtitle()->value();
$json['theme'] = $page->theme()->or('default')->value();

$json['introTitle'] = $page->introTitle()->value();
$json['intro'] = $page->intro()->value();
$json['introLayout'] = $page->introLayout()->or('1col')->value();
$introTitleImageFile = $page->introTitleImage()->toFile();
$json['introTitleImage'] = $introTitleImageFile ? Utils::getJsonEncodeImageData($introTitleImageFile) : null;
$json['introCta'] = Utils::resolveCtaStructure($page->introCta());

$coverFile = $page->cover()->toFile();
$json['cover'] = $coverFile ? Utils::getJsonEncodeImageData($coverFile) : null;

$blocks = [];
foreach ($page->body()->toBlocks() as $block) {
    $content = [];
    if ($block->type() === 'module-titre-texte-image') {
        $imageFile  = $block->image()->toFile();
        $content = [
            'title'         => $block->title()->value(),
            'description'   => $block->description()->value(),
            'image'         => $imageFile ? Utils::getJsonEncodeImageData($imageFile) : null,
            'imagePosition' => $block->content()->get('imagePosition')->or('right')->value(),
            'variant'       => $block->variant()->or('default')->value(),
            'cta'           => Utils::resolveCtaStructure($block->cta()),
        ];
    } elseif ($block->type() === 'module-cta') {
        $content = Utils::getCtaModuleData($block);
    } elseif ($block->type() === 'module-partenaires') {
        $partners = [];
        foreach ($block->partners()->toStructure() as $partner) {
            $logoFile = $partner->logo()->toFile();
            if (!$logoFile) continue;
            $partners[] = [
                'logo'  => Utils::getJsonEncodeImageData($logoFile),
                'url'   => $partner->url()->isNotEmpty() ? $partner->url()->value() : null,
                // The logo alt is the accessible name; fall back to the link target.
                'label' => $logoFile->alt()->isNotEmpty()
                    ? $logoFile->alt()->value()
                    : $partner->url()->value(),
            ];
        }
        $content = [
            'title'    => $block->title()->value(),
            'subtitle' => $block->subtitle()->isNotEmpty() ? $block->subtitle()->value() : null,
            'partners' => $partners,
            'variant'  => $block->variant()->or('default')->value(),
        ];
    } elseif ($block->type() === 'module-cases') {
        $rows = [];
        foreach ($block->rows()->toStructure() as $row) {
            $rows[] = [
                'title'       => $row->title()->value(),
                'hideTitle'   => $row->hideTitle()->toBool(),
                'description' => $row->description()->value(),
                'cta'         => Utils::resolveCtaStructure($row->cta()),
                'media'       => Utils::getJsonEncodeMediaArray($row->media()->toFiles()),
            ];
        }
        $content = [
            'title'     => $block->title()->value(),
            'hideTitle' => $block->hideTitle()->toBool(),
            'intro'     => $block->intro()->value(),
            'rows'      => $rows,
            'layout'    => $block->layout()->or('alternate')->value(),
            'variant'   => $block->variant()->or('default')->value(),
        ];
    } elseif ($block->type() === 'module-grille-images') {
        $images = [];
        foreach ($block->images()->toStructure() as $item) {
            $imageFile = $item->image()->toFile();
            if (!$imageFile) continue;
            $images[] = [
                'image' => Utils::getJsonEncodeImageData($imageFile),
                'title' => $item->title()->value(),
            ];
        }
        $content = [
            'title'     => $block->title()->value(),
            'shortDesc' => $block->shortDesc()->isNotEmpty() ? $block->shortDesc()->value() : null,
            'images'    => $images,
            'cta'       => Utils::resolveCtaStructure($block->cta()),
            'variant'   => $block->variant()->or('default')->value(),
        ];
    } elseif ($block->type() === 'module-infos-pratiques') {
        // Either empty or exactly 3 title/description pairs (enforced by the blueprint).
        $elements = [];
        $row = $block->elements()->toStructure()->first();
        if ($row) {
            foreach ([1, 2, 3] as $i) {
                $elements[] = [
                    'title'       => $row->{'title' . $i}()->value(),
                    'description' => $row->{'description' . $i}()->value(),
                ];
            }
        }

        $categorySlugs = array_column(Utils::resolveTaxonomyTerms($block->faqCategories(), 'faq-categories'), 'slug');

        // All matching FAQ questions, pulled from the FAQ page.
        $faqPage = $site->index()->template('faq')->first();
        $faqs = [];
        if ($faqPage) {
            foreach ($faqPage->sections()->toStructure() as $faqSection) {
                foreach (Utils::filterStructureByTaxonomy($faqSection->faqs()->toStructure(), 'faqCategories', $categorySlugs) as $faq) {
                    $faqs[] = [
                        'question' => $faq->question()->value(),
                        'answer'   => $faq->answer()->value(),
                    ];
                }
            }
        }

        // The CTA URL is resolved here so a FAQ slug change never breaks the frontend link.
        $ctaUrl = $faqPage
            ? '/' . $faqPage->virtualPath() . ($categorySlugs !== [] ? '?faqCategories=' . implode(',', $categorySlugs) : '')
            : null;

        $content = [
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
    } elseif ($block->type() === 'module-agenda') {
        $filters = [
            'domains'     => array_column(Utils::resolveTaxonomyTerms($block->domains(), 'domains'), 'slug'),
            'eventThemes' => array_column(Utils::resolveTaxonomyTerms($block->eventThemes(), 'event-themes'), 'slug'),
        ];

        $eventsPage = $site->index()->template('events')->first();
        $events = [];
        if ($eventsPage) {
            $children = $eventsPage->children()->listed();
            foreach ($filters as $field => $slugs) {
                $children = Utils::filterPagesByTaxonomy($children, $field, $slugs);
            }

            // Upcoming events only (an event stays listed until it is over), soonest first.
            $children = Utils::splitEventsByDate($children)['upcoming'];

            $events = array_values($children->map(fn($event) => Utils::getEventCardData($event))->data());
        }

        // Resolved here so an agenda slug change never breaks the frontend link.
        $ctaUrl = $eventsPage ? '/' . $eventsPage->virtualPath() . Utils::buildTaxonomyQuery($filters) : null;

        $content = [
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
    } elseif ($block->type() === 'module-projets') {
        $filters = [
            'projectThemes' => array_column(Utils::resolveTaxonomyTerms($block->projectThemes(), 'project-themes'), 'slug'),
            'projectTypes'  => array_column(Utils::resolveTaxonomyTerms($block->projectTypes(), 'project-types'), 'slug'),
        ];

        $projectsPage = $site->index()->template('projects')->first();
        $projects = [];
        if ($projectsPage) {
            $children = $projectsPage->children()->listed();
            foreach ($filters as $field => $slugs) {
                $children = Utils::filterPagesByTaxonomy($children, $field, $slugs);
            }

            $projects = array_values($children->map(fn($project) => Utils::getProjectCardData($project))->data());
        }

        $ctaUrl = $projectsPage ? '/' . $projectsPage->virtualPath() . Utils::buildTaxonomyQuery($filters) : null;

        $content = [
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
    } else {
        $content = $block->toArray()['content'] ?? [];
    }
    $blocks[] = [
        'id'       => $block->id(),
        'type'     => $block->type(),
        'isHidden' => $block->isHidden(),
        'content'  => $content,
    ];
}
$json['body'] = $blocks;

$json['seo'] = Utils::getSeoDataFromPage($page);

$json['trackWithMatomo'] = $page->trackWithMatomo()->toBool();

$json['path'] = $page->virtualPath();

$parentPage = $page->parentPage()->toPage();
$json['parentPage'] = $parentPage ? [
    'title' => $parentPage->title()->value(),
    'slug'  => $parentPage->slug(),
    'path'  => $parentPage->virtualPath(),
] : null;

echo json_encode($json);
