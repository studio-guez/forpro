<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'job-offer');

// The index the "back" link points to, i.e. the real Kirby parent (job-offers).
$json['parentPage'] = Utils::getParentPageData($page);

$json['sectors'] = Utils::resolveTaxonomyTerms($page->sectors(), 'sectors');

// Closed offers stay reachable at their URL: the frontend replaces the
// application details by a notice rather than 404ing.
$json['openToApplications'] = Utils::isOpenToApplications($page);

$json['description'] = $page->description()->value();
$json['profile']     = $page->profile()->value();
$json['conditions']  = $page->conditions()->value();

$json['location'] = $page->location()->value();
$json += Utils::getActivityRate($page);
$json['startDate'] = $page->startDate()->value();
$json['deadline']  = $page->deadline()->toDate('Y-m-d');

// Closed offers ship no address to apply to, not even in the page payload.
$json['applicationEmail']   = $json['openToApplications'] ? $page->applicationEmail()->value() : null;
$json['pdfOffer']           = Utils::getJsonEncodeDocumentDataOrNull($page->pdfOffer()->toFile());
$json['applicationContent'] = $page->applicationContent()->value();

$json['applicationQuestions'] = array_values($page->applicationQuestions()->toStructure()->map(fn($item) => [
    'question' => $item->question()->value(),
    'answer'   => $item->answer()->value(),
])->data());

$json['recruitingSteps'] = Utils::getTimelineSteps($page->recruitingSteps());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
