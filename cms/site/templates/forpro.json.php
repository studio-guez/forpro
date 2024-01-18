<?php

$json = [];

$hero = $page->hero()->toStructure()?->get(0);
$showMenu = $page->showMenu()->toBool();
$showNewsletter = $page->showNewsletter()->toBool();
$body = $page->body()->toBlocks()->toArray();

function getValueNotEmpty($pageAttribute, $siteAttribute) {
    if($pageAttribute->isNotEmpty()) {
        return $pageAttribute->value();
    } elseif($siteAttribute->isNotEmpty()) {
        return $siteAttribute->value();
    }
    return "";
}

$json['page'] = [
    'showMenu' => $showMenu,
    'showNewsletter' => $showNewsletter,
    'hero' => $hero,
];

$json['body'] = $body;

$json['seo'] = [
    // General ===========================================================
    'metaTemplate'      => getValueNotEmpty($page->metaTemplate(), $site->metaTemplate()),
    'metaDescription'   => getValueNotEmpty($page->metaDescription(), $site->metaDescription()),
    'metaAuthor'        => getValueNotEmpty($page->metaAuthor(), $site->metaAuthor()),
    'metaImage'         => getValueNotEmpty($page->metaImage(), $site->metaImage()),
    'metaPhoneNumber'   => getValueNotEmpty($page->metaPhoneNumber(), $site->metaPhoneNumber()),
    // Facebook ===========================================================
    'ogTemplate'        => getValueNotEmpty($page->ogTemplate(), $site->ogTemplate()),
    'ogDescription'     => getValueNotEmpty($page->ogDescription(), $site->ogDescription()),
    'ogImage'           => getValueNotEmpty($page->ogImage(), $site->ogImage()),
    'ogSiteName'        => getValueNotEmpty($page->ogSiteName(), $site->ogSiteName()),
    // Twitter ===========================================================
    'twitterTemplate'   => getValueNotEmpty($page->twitterTemplate(), $site->twitterTemplate()),
    'twitterDescription'=> getValueNotEmpty($page->twitterDescription(), $site->twitterDescription()),
    'twitterImage'      => getValueNotEmpty($page->twitterImage(), $site->twitterImage()),
    'twitterCardType'   => getValueNotEmpty($page->twitterCardType(), $site->twitterCardType()),
    'twitterSite'       => getValueNotEmpty($page->twitterSite(), $site->twitterSite()),
    'twitterCreator'    => getValueNotEmpty($page->twitterCreator(), $site->twitterCreator()),
];

echo json_encode($json);





