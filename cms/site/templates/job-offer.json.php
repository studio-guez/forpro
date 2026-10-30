<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'job-offer');

$json['parentPage'] = Utils::getParentPageData($page);

$json['sector'] = $page->sector()->isNotEmpty() ? $page->sector()->value() : null;

// Closed offers stay reachable: the frontend shows a notice instead of the application details.
$json['openToApplications'] = Utils::isOpenToApplications($page);

$json['description'] = Utils::getRichText($page->description());
$json['profile']     = Utils::getRichText($page->profile());
$json['conditions']  = Utils::getRichText($page->conditions());

$json['location'] = $page->location()->value();
$json += Utils::getActivityRate($page);
$json['startDate'] = $page->startDate()->value();
$json['deadline']  = $page->deadline()->toDate('Y-m-d');

// Closed offers ship no address to apply to, not even in the page payload.
$json['applicationEmail']   = $json['openToApplications'] ? $page->applicationEmail()->value() : null;
$json['pdfOffer']           = Utils::getJsonEncodeDocumentDataOrNull($page->pdfOffer()->toFile());
$json['applicationContent'] = Utils::getRichText($page->applicationContent());

$json['applicationQuestions'] = array_values($page->applicationQuestions()->toStructure()->map(fn($item) => [
    'question' => $item->question()->value(),
    'answer'   => Utils::getRichText($item->answer()),
])->data());

$json['recruitingSteps'] = Utils::getTimelineSteps($page->recruitingSteps());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
