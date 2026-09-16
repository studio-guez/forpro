<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'projects');

$json['noResultsText'] = $page->noResultsText()->value();

$json['sectors']    = Utils::getTaxonomyTerms('sectors');
$json['categories'] = Utils::getTaxonomyTerms('categories');

$projects = $page->children()->listed();

$json['usedSectors']    = Utils::getUsedTaxonomySlugs($projects, 'sectors');
$json['usedCategories'] = Utils::getUsedTaxonomySlugs($projects, 'categories');

$json['years'] = Utils::getProjectYears($projects);

// Filters come from the query string so a shared/reloaded URL doesn't first paint unrelated projects and swap them on hydration.
$json['projects'] = Utils::getProjects(
    $projects,
    mb_substr((string)(get('q') ?? ''), 0, 100),
    array_filter(explode(',', (string)(get('sectors') ?? ''))),
    array_filter(explode(',', (string)(get('categories') ?? ''))),
    array_filter(explode(',', (string)(get('years') ?? '')))
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
