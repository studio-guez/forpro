<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();

// Optional pre-filtering by domain: /faq.json?domains=slug-a,slug-b
$domains = array_values(array_filter(
    array_slice(explode(',', (string)get('domains')), 0, 20),
    fn(string $slug) => preg_match('/^[a-z0-9-]+$/', $slug) === 1
));

$json['sections'] = $page->sections()->toStructure()->map(function ($section) use ($domains) {
    $faqs = Utils::filterStructureByTaxonomy($section->faqs()->toStructure(), 'domains', $domains);

    return [
        'title' => $section->sectiontitle()->value(),
        'faqs'  => $faqs->map(fn($item) => [
            'question' => $item->question()->value(),
            'answer'   => $item->answer()->value(),
            'domains'  => Utils::resolveTaxonomyTerms($item->domains(), 'domains'),
        ])->values(),
    ];
})->values();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
