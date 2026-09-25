<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'infos-pratiques');

$json['openingHoursTitle']   = $page->openingHoursTitle()->value();
$json['openingHoursContent'] = Utils::getRichText($page->openingHoursContent());

$json['foodlabOpeningHoursTitle'] = $page->foodlabOpeningHoursTitle()->value();
$json['foodlabImage']             = Utils::getJsonEncodeImageDataOrNull($page->foodlabImage()->toFile());
$json['foodlabCta']               = Utils::resolveCtaStructure($page->foodlabCta());

$json['accessTitle']   = $page->accessTitle()->value();
$json['accessContent'] = Utils::getRichText($page->accessContent());
$json['mapImage']      = Utils::getJsonEncodeImageDataOrNull($page->mapImage()->toFile());
$json['mapUrl']        = $page->mapUrl()->isNotEmpty() ? $page->mapUrl()->value() : null;

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
