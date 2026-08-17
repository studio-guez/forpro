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

$faqs = Utils::filterStructureByTaxonomy($page->faqs()->toStructure(), 'domains', $domains);

$json['faqs'] = $faqs->map(fn($item) => [
    'question' => $item->question()->value(),
    'answer'   => $item->answer()->value(),
    'domains'  => Utils::resolveTaxonomyTerms($item->domains(), 'domaines'),
])->values();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
