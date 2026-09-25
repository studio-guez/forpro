<?php

use Kirby\Cms\Page;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

return [
	'page.update:after' => function (Page $newPage, Page $oldPage) {
		// only inject blueprint defaults if the seo tab is present
		if (!$newPage->blueprint()->tab('seo')) {
			return;
		}

		$updates = A::reduce(
			$newPage->kirby()->option('tobimori.seo.robots.types'),
			function ($carry, $robots) use ($newPage) {
				$upper = Str::ucfirst($robots);
				if ($newPage->content()->get("robots{$upper}")->value() === "") {
					$carry["robots{$upper}"] = 'default';
				}

				return $carry;
			},
			[]
		);

		if (A::count($updates)) {
			$newPage->update($updates, $newPage->kirby()->languageCode());
		}
	},
	'page.render:before' => function (string $contentType, array $data, Page $page) {
		if (option('tobimori.seo.generateSchema')) {
			$page->schema('WebSite')
				->url($page->metadata()->canonicalUrl())
				->copyrightYear(date('Y'))
				->description($page->metadata()->metaDescription())
				->name($page->metadata()->metaTitle())
				->headline($page->metadata()->title());
		}
	},
];
