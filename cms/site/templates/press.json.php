<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'press');

$json['contactTitle'] = $page->contactTitle()->value();

$json['contactPersons'] = $page->contactPersons()->toStructure()->map(fn($person) => [
    'name'  => $person->name()->value(),
    'role'  => $person->role()->value(),
    'email' => $person->email()->value(),
    'phone' => $person->phone()->value(),
])->values();

$json['resourcesTitle']    = $page->resourcesTitle()->value();
$json['resourcesCtaTitle'] = $page->resourcesCtaTitle()->value();
$json['resources']         = Utils::getJsonEncodeDocumentDataOrNull($page->resources()->toFile());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
