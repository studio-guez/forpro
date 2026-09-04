<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'factory-lab');

$json['overtitle'] = $page->overtitle()->value();
$json['theme'] = $page->theme()->or('default')->value();
$json['headerType'] = $page->headerType()->or('full')->value();

$json['introTitle'] = $page->introTitle()->value();
$json['intro'] = $page->intro()->value();
$json['introLayout'] = $page->introLayout()->or('1col')->value();
$json['introTitleImage'] = Utils::getJsonEncodeImageDataOrNull($page->introTitleImage()->toFile());
$json['introCta'] = Utils::resolveCtaStructure($page->introCta());

$json['cover'] = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());

// Same `label` + optional `url` pair on the trainings and on the availability badges.
$resolveBadges = fn(\Kirby\Content\Field $field) => $field->toStructure()->map(fn($badge) => [
    'label' => $badge->label()->value(),
    'url'   => $badge->url()->isNotEmpty() ? $badge->url()->value() : null,
])->values();

$json['companiesModule'] = [
    'title'   => $page->companiesTitle()->value(),
    'intro'   => $page->companiesIntro()->isNotEmpty() ? $page->companiesIntro()->value() : null,
    'variant' => $page->companiesVariant()->or('default')->value(),
    'labels'  => [
        'trainings'    => $page->companiesTrainingsLabel()->value(),
        'availability' => $page->companiesAvailabilityLabel()->value(),
        'followUp'     => $page->companiesFollowUpLabel()->value(),
    ],
    'companies' => $page->companies()->toStructure()->map(fn($company) => [
        'image'        => Utils::getJsonEncodeImageDataOrNull($company->image()->toFile()),
        'title'        => $company->title()->value(),
        'url'          => $company->url()->isNotEmpty() ? $company->url()->value() : null,
        'description'  => $company->description()->isNotEmpty() ? $company->description()->value() : null,
        'trainings'    => $resolveBadges($company->trainings()),
        'availability' => $resolveBadges($company->availability()),
        'followUps'    => $company->followUps()->toStructure()->map(fn($item) => $item->label()->value())->values(),
    ])->values(),
];

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

$parentPage = $page->parentPage()->toPage();
$json['parentPage'] = $parentPage ? [
    'title' => $parentPage->title()->value(),
    'slug'  => $parentPage->slug(),
    'path'  => $parentPage->virtualPath(),
] : null;

echo json_encode($json);
