<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'home');

// Empty `alt` = decorative: the frontend hides the canvas from assistive tech.
$lottieData = fn(?\Kirby\Cms\File $lottie, ?\Kirby\Cms\File $poster) => $lottie ? [
    ...Utils::getJsonEncodeDocumentDataOrNull($lottie),
    'alt'    => $lottie->alt()->value(),
    'poster' => Utils::getJsonEncodeImageDataOrNull($poster),
] : null;
$poster = $page->lottiePoster()->toFile();
$json['lottie']       = $lottieData($page->lottie()->toFile(), $poster);
$json['lottieMobile'] = $lottieData($page->lottieMobile()->toFile(), $page->lottiePosterMobile()->toFile() ?? $poster);

$json['welcomeTitle']     = $page->welcomeTitle()->value();
$json['welcomeShortDesc'] = $page->welcomeShortDesc()->value();

$json['welcomeCards'] = array_map(fn(int $index) => [
    'title' => $page->{"welcomeCardTitle{$index}"}()->value(),
    'cta'   => Utils::resolveCtaStructure($page->{"welcomeCardCta{$index}"}()),
], [1, 2, 3]);

$json['resourcesTitle'] = $page->resourcesTitle()->value();

$card = fn(string $type, ?string $overtitle, string $title, string $shortDesc, ?\Kirby\Cms\File $cover, \Kirby\Cms\Page $target) => [
    'type'      => $type,
    'overtitle' => $overtitle !== '' ? $overtitle : null,
    'title'     => $title,
    'shortDesc' => $shortDesc !== '' ? $shortDesc : null,
    'cover'     => Utils::getJsonEncodeImageDataOrNull($cover),
    'url'       => '/' . $target->virtualPath(),
];

$pageCards = [];
foreach ($page->resourcesAvailablePages()->toStructure() as $entry) {
    $target = $entry->page()->toPage();
    if ($target === null) {
        continue;
    }
    $pageCards[] = $card(
        'page',
        $entry->overtitle()->value(),
        $entry->title()->or($target->title())->value(),
        $entry->shortDesc()->or($target->shortDesc())->or($target->intro())->value(),
        $entry->cover()->toFile() ?? $target->cover()->toFile(),
        $target
    );
}

$projectsOvertitle = $page->projectsOvertitle()->value();
$projectsPage = site()->index()->template('projects')->first();
$projectCards = $projectsPage
    ? $projectsPage->children()->listed()->map(fn(\Kirby\Cms\Page $project) => $card(
        'project',
        $projectsOvertitle,
        $project->title()->value(),
        $project->shortDesc()->value(),
        $project->cover()->toFile(),
        $project
    ))->values()
    : [];

shuffle($pageCards);
shuffle($projectCards);
$resources = [...array_slice($pageCards, 0, 3), ...array_slice($projectCards, 0, 2)];
shuffle($resources);
$json['resources'] = $resources;

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
