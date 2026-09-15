<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'job-offers');

$json['cover'] = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());

$json['headerType'] = $page->headerType()->or('compact')->value();
$json['overtitle'] = $page->overtitle()->value();
$json['introTitle'] = $page->introTitle()->value();
$json['intro'] = $page->intro()->value();

$json['jobOffers'] = array_values(Utils::filterOpenToApplications($page->children()->listed())
    ->map(fn($offer) => Utils::getJobOfferCardData($offer))->data());

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
