<?php

require_once 'utils/Utils.php';

/** @global Kirby\Cms\App $kirby */
/** @global Kirby\Cms\Site $site */
/** @global Kirby\Cms\Page $page */

$json = Utils::getPageBaseData($page, 'team');

$json['cover'] = Utils::getJsonEncodeImageDataOrNull($page->cover()->toFile());

$json['headerType'] = $page->headerType()->or('compact')->value();
$json['overtitle'] = $page->overtitle()->value();
$json['introTitle'] = $page->introTitle()->value();
$json['intro'] = $page->intro()->value();

$json['sections'] = $page->sections()->toStructure()->map(fn($section) => [
    'title'   => $section->title()->value(),
    'members' => $section->members()->toStructure()->map(fn($member) => [
        'name'     => $member->name()->value(),
        'role'     => $member->role()->isNotEmpty() ? $member->role()->value() : null,
        'status'   => $member->status()->isNotEmpty() ? $member->status()->value() : null,
        'linkedin' => $member->linkedin()->isNotEmpty() ? $member->linkedin()->value() : null,
    ])->values(),
])->values();

$json['body'] = Utils::getBodyBlocks($page->body());

$json['seo'] = Utils::getSeoDataFromPage($page);

echo json_encode($json);
