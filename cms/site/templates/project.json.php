<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getEventProjectBaseData($page);

$json['template'] = 'project';

$json['programs']   = Utils::resolveTaxonomyTerms($page->programs(), 'programs');
$json['categories'] = Utils::resolveTaxonomyTerms($page->categories(), 'categories');

$json['collectiveName']    = $page->collectiveName()->value();
$json['collectiveMembers'] = array_values($page->collectiveMembers()->toStructure()->map(fn($item) => [
    'name' => $item->name()->value(),
])->data());

echo json_encode($json);
