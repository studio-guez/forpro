<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'projects');

// All terms in their CMS-defined order, so the frontend can order filters accordingly.
$json['projectThemes'] = Utils::getTaxonomyTerms('project-themes');
$json['projectTypes']  = Utils::getTaxonomyTerms('project-types');

$json['projects'] = array_values($page->children()->listed()
    ->map(fn($project) => Utils::getProjectCardData($project))
    ->data());

// Years actually used by a project, most recent first: the year field is only a filter.
$years = array_values(array_unique(array_column($json['projects'], 'year')));
rsort($years);
$json['years'] = $years;

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
