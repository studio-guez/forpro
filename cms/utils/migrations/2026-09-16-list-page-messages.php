<?php

/**
 * The empty-state messages of the list pages (no search result, no upcoming
 * event, no open job offer, ...) used to be hardcoded in the frontend; they
 * are now writer fields of the listing page. Blueprint defaults only apply when
 * a page is created, so the existing pages get the former hardcoded wording
 * here. A message already set by hand is left alone.
 */

$messages = [
    'events'     => [
        'noResultsText'     => '<p>Aucun événement à venir ne correspond à votre recherche.</p>',
        'noUpcomingText'    => '<p>Aucun événement à venir pour le moment.</p>',
        'noPastResultsText' => '<p>Aucun événement passé ne correspond à votre recherche.</p>',
    ],
    'faq'        => [
        'noResultsText' => '<p>Aucune question ne correspond à votre recherche.</p>',
    ],
    'job-offers' => [
        'noOffersText' => "<p>Aucune offre d'emploi n'est ouverte pour le moment.</p>",
    ],
    'missions'   => [
        'noResultsText' => '<p>Aucune mission ne correspond à votre sélection.</p>',
    ],
    'projects'   => [
        'noResultsText' => '<p>Aucun projet ne correspond à votre recherche.</p>',
    ],
];

return [
    'description' => 'List pages: seed the empty-state message fields with the wording the frontend used to hardcode',
    'run'         => function (Kirby\Cms\App $kirby, bool $dryRun) use ($messages): int {
        $changed = 0;

        foreach ($messages as $template => $defaults) {
            foreach ($kirby->site()->index(true)->template($template) as $page) {
                foreach (['latest', 'changes'] as $versionId) {
                    $version = $page->version($versionId);

                    if ($version->exists() === false) {
                        continue;
                    }

                    // Content files store field keys lowercased.
                    $fields = $version->read() ?? [];
                    $update = [];

                    foreach ($defaults as $key => $text) {
                        if (trim((string)($fields[strtolower($key)] ?? '')) === '') {
                            $update[$key] = $text;
                        }
                    }

                    if ($update === []) {
                        continue;
                    }

                    echo "  {$page->id()} [{$versionId}]: " . implode(', ', array_keys($update)) . "\n";

                    if ($dryRun === false) {
                        $version->update($update);
                    }

                    $changed++;
                }
            }
        }

        return $changed;
    },
];
