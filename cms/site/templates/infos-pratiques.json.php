<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'infos-pratiques');

$json['openingHoursTitle']   = $page->openingHoursTitle()->value();
$json['openingHoursContent'] = $page->openingHoursContent()->isNotEmpty() ? $page->openingHoursContent()->value() : null;

$json['foodlabOpeningHoursTitle'] = $page->foodlabOpeningHoursTitle()->value();
$json['foodlabImage']             = Utils::getJsonEncodeImageDataOrNull($page->foodlabImage()->toFile());
$json['foodlabCta']               = Utils::resolveCtaStructure($page->foodlabCta());

$json['accessTitle']   = $page->accessTitle()->value();
$json['accessContent'] = $page->accessContent()->isNotEmpty() ? $page->accessContent()->value() : null;
$json['mapEmbedUrl']   = Utils::getGoogleMapsEmbedUrl($page->mapEmbedUrl()->value());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
