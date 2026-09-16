<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'faq');

$json['noResultsText'] = $page->noResultsText()->value();

$json['sectors']  = Utils::getTaxonomyTerms('sectors');
$json['programs'] = Utils::getTaxonomyTerms('programs');
$json['publics']  = Utils::getTaxonomyTerms('publics');

// Every question ships regardless of the query string: the frontend filters the whole list itself
// and derives its dropdowns from it, so a prefiltered payload would hide questions and options for good.
$json['sections'] = $page->sections()->toStructure()->map(function ($section) {
    return [
        'title' => $section->title()->value(),
        'faqs'  => $section->faqs()->toStructure()->map(fn($item) => [
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
