<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [];

$json['template'] = 'faq';
$json['title'] = $page->title()->value();
$json['slug'] = $page->slug();
$json['path'] = $page->virtualPath();

// All FAQ categories in their CMS-defined order, so the frontend can order filters accordingly.
$json['faqCategories'] = Utils::getTaxonomyTerms('faq-categories');

// Optional pre-filtering by category: /faq.json?faqCategories=slug-a,slug-b
$faqCategories = array_values(array_filter(
    array_slice(explode(',', (string)get('faqCategories')), 0, 20),
    fn(string $slug) => preg_match('/^[a-z0-9-]+$/', $slug) === 1
));

$json['sections'] = $page->sections()->toStructure()->map(function ($section) use ($faqCategories) {
    $faqs = Utils::filterStructureByTaxonomy($section->faqs()->toStructure(), 'faqCategories', $faqCategories);

    return [
        'title' => $section->title()->value(),
        'faqs'  => $faqs->map(fn($item) => [
            'question'      => $item->question()->value(),
            'answer'        => $item->answer()->value(),
            'faqCategories' => Utils::resolveTaxonomyTerms($item->faqCategories(), 'faq-categories'),
        ])->values(),
    ];
})->values();

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
