<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'faq');

$json['sectors']  = Utils::getTaxonomyTerms('sectors');
$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

$readSlugs = fn(string $param): array => array_values(array_filter(
    array_slice(explode(',', (string)get($param)), 0, 20),
    fn(string $slug) => preg_match('/^[a-z0-9-]+$/', $slug) === 1
));

$filters = [
    'sectors'  => $readSlugs('sectors'),
    'programs' => $readSlugs('programs'),
    'publics'  => $readSlugs('publics'),
];

$json['sections'] = $page->sections()->toStructure()->map(function ($section) use ($filters) {
    $faqs = $section->faqs()->toStructure();
    foreach ($filters as $field => $slugs) {
        $faqs = Utils::filterStructureByTaxonomy($faqs, $field, $slugs);
    }

    return [
        'title' => $section->title()->value(),
        'faqs'  => $faqs->map(fn($item) => [
            'question' => $item->question()->value(),
            'answer'   => $item->answer()->value(),
            'sectors'  => Utils::resolveTaxonomyTerms($item->sectors(), 'sectors'),
            'programs' => Utils::resolveTaxonomyTerms($item->programs(), 'programs'),
            'publics'  => Utils::resolveTaxonomyTerms($item->publics(), 'publics'),
        ])->values(),
    ];
})->values();

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
