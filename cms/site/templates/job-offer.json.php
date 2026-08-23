<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'job-offer');

// The index the "back" link points to, i.e. the real Kirby parent (job-offers).
$parent = $page->parent();
$json['parentPage'] = $parent ? [
    'title' => $parent->title()->value(),
    'slug'  => $parent->slug(),
    'path'  => $parent->virtualPath(),
] : null;

$json['domains']            = Utils::resolveTaxonomyTerms($page->domains(), 'domains');
$json['jobOfferCategories'] = Utils::resolveTaxonomyTerms($page->jobOfferCategories(), 'job-offer-categories');

$json['description'] = $page->description()->value();
$json['profile']     = $page->profile()->value();
$json['conditions']  = $page->conditions()->value();

$json['location'] = $page->location()->value();
$json += Utils::getActivityRate($page);
$json['startDate'] = $page->startDate()->value();
$json['deadline']  = $page->deadline()->toDate('Y-m-d');

$json['applicationEmail']   = $page->applicationEmail()->value();
$json['pdfOffer']           = Utils::getJsonEncodeDocumentDataOrNull($page->pdfOffer()->toFile());
$json['applicationContent'] = $page->applicationContent()->value();

$json['applicationQuestions'] = array_values($page->applicationQuestions()->toStructure()->map(fn($item) => [
    'question' => $item->question()->value(),
    'answer'   => $item->answer()->value(),
])->data());

$json['recruitingSteps'] = array_values($page->recruitingSteps()->toStructure()->map(fn($item) => [
    'title'     => $item->title()->value(),
    'shortDesc' => $item->shortDesc()->value(),
])->data());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
