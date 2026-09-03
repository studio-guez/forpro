<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'projects');

// All terms in their CMS-defined order, so the frontend can order filters accordingly.
$json['programs']   = Utils::getTaxonomyTerms('programs');
$json['categories'] = Utils::getTaxonomyTerms('categories');

$projects = $page->children()->listed();

// Only the terms actually carried by a project, so the frontend can offer
// filters that lead somewhere without being shipped the whole archive.
$json['usedPrograms']   = Utils::getUsedTaxonomySlugs($projects, 'programs');
$json['usedCategories'] = Utils::getUsedTaxonomySlugs($projects, 'categories');

// Years actually used by a project, most recent first: the year field is only a
// filter, so it is read off the collection rather than off the (paginated) cards.
$years = array_values(array_unique($projects->values(fn($project) => (int)$project->year()->value())));
rsort($years);
$json['years'] = $years;

// First page of the archive. The filters are read off the query string so a
// shared or reloaded `?q=…&programs=…&years=…` URL renders the projects it
// actually asks for — otherwise the first paint would show twelve unrelated
// ones and swap them out on hydration. Later pages come from `/projects.json`.
$json['projects'] = Utils::getProjects(
    $projects,
    mb_substr((string)(get('q') ?? ''), 0, 100),
    array_filter(explode(',', (string)(get('programs') ?? ''))),
    array_filter(explode(',', (string)(get('categories') ?? ''))),
    array_filter(explode(',', (string)(get('years') ?? '')))
);

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
