<?php

require_once 'utils/ContentApi.php';

/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = [
    'pageInfo' => ContentApi::project($page),
    'seo'      => ContentApi::seo($page, $site),
];

echo json_encode($json);
