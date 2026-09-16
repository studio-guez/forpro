<?php

/**
 * Projects used to carry a `year` number field, which could only filter them;
 * they now carry a `date` field, which also orders them. Only the year is
 * known, so every migrated project lands on the 1st of January of its year -
 * the real date is to be set by hand in the Panel.
 *
 * Both content versions are migrated: `latest`, and `changes` when a Panel
 * user has unpublished edits. Publishing those edits later would otherwise
 * bring `year` back and wipe `date`.
 */

return [
    'description' => 'Projects: replace the `year` number field with a `date` field (1st of January of that year)',
    'run'         => function (Kirby\Cms\App $kirby, bool $dryRun): int {
        $changed = 0;

        foreach ($kirby->site()->index(true)->template('project') as $project) {
            foreach (['latest', 'changes'] as $versionId) {
                $version = $project->version($versionId);

                if ($version->exists() === false) {
                    continue;
                }

                $fields = $version->read() ?? [];

                if (array_key_exists('year', $fields) === false) {
                    continue;
                }

                $year = (int)$fields['year'];
                $date = $year > 0 ? sprintf('%04d-01-01', $year) : '';

                // A date already set by hand wins over the one derived from the year.
                $update = ['year' => null];
                if (($fields['date'] ?? '') === '') {
                    $update['date'] = $date;
                }

                echo "  {$project->id()} [{$versionId}]: year {$fields['year']} -> date " . ($update['date'] ?? $fields['date']) . "\n";

                if ($dryRun === false) {
                    // Txt::encode() drops null values, so this removes `year` from the file.
                    $version->update($update);
                }

                $changed++;
            }
        }

        return $changed;
    },
];
