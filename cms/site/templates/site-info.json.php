<?php

require_once 'utils/Utils.php';

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

echo json_encode([
    'title' => $site->title()->value(),
    'nav'   => array_values($site->children()->map(fn($kirbyPage) => [
        'title'     => $kirbyPage->title()->value(),
        'heroTitle' => $kirbyPage->content()->heroTitle()->value(),
        'showmenu'  => (boolean)$kirbyPage->showmenu()->value(),
        'slug'      => $kirbyPage->slug(),
        'url'       => $kirbyPage->url(),
        'uri'       => $kirbyPage->uri(),
        'hero'      => Utils::getHeroFromPage($kirbyPage),
    ])->data()),
    'footer'     => $site->page()->content()->footer()->value(),
]);

//'title' => $page->title()->value(),
//'slug' => $page->slug(),
//'url' => $page->url(),
