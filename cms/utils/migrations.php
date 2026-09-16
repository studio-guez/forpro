<?php

/**
 * migrations.php
 *
 * Runs the content migrations under `utils/migrations/`, in file-name order.
 * Content is not versioned in git and each environment (dev, preprod, prod)
 * has its own, so a schema change ships as a migration here and is applied
 * on every environment once the matching code is deployed.
 *
 * A migration is a PHP file returning
 *   ['description' => string, 'run' => fn (Kirby\Cms\App $kirby, bool $dryRun): int]
 * where `run()` returns the number of pages it changed (or would change) and
 * prints one line per page. Name the file `YYYY-MM-DD-<what>.php` so the
 * order is the order they were written in.
 *
 * Migrations must be idempotent: rerunning one on already-migrated content is
 * a no-op. There is deliberately no "applied" ledger - a ledger stored with the
 * content would be lost with it, one stored with the code would lie about the
 * other environments. Idempotency lets the whole set run on every environment
 * whenever it is convenient, as many times as needed.
 *
 * Usage (run from the CMS root, inside the container, as www-data):
 *   php utils/migrations.php --dry-run             # report what every migration would change
 *   php utils/migrations.php                       # apply every migration
 *   php utils/migrations.php <name> [<name> ...]   # apply only these (file name without .php)
 *
 * Writes go through Kirby's Version API, so both the published content and
 * pending Panel changes (`_changes/`) are migrated, and a page locked by a
 * Panel user is reported and skipped rather than clobbered.
 *
 * @author    Octoplus Solutions
 * @license   Proprietary - All rights reserved. Not free for use.
 */

require_once __DIR__ . '/../kirby/bootstrap.php';

$root   = dirname(__DIR__);
$dryRun = in_array('--dry-run', $argv, true);
$only   = array_values(array_filter(array_slice($argv, 1), fn(string $arg) => str_starts_with($arg, '--') === false));

$kirby = new Kirby\Cms\App(['roots' => ['index' => $root]]);

// Page::update() and friends check permissions, which need a user.
$kirby->impersonate('kirby');

$files = glob(__DIR__ . '/migrations/*.php') ?: [];
sort($files);

if ($only !== []) {
    $files = array_values(array_filter(
        $files,
        fn(string $file) => in_array(basename($file, '.php'), $only, true)
    ));

    $missing = array_diff($only, array_map(fn(string $file) => basename($file, '.php'), $files));
    if ($missing !== []) {
        fwrite(STDERR, 'Unknown migration(s): ' . implode(', ', $missing) . "\n");
        exit(1);
    }
}

if ($files === []) {
    echo "No migrations found in utils/migrations/.\n";
    exit(0);
}

echo ($dryRun ? 'Dry run: ' : 'Running ') . count($files) . " migration(s)\n";

$total  = 0;
$failed = 0;

foreach ($files as $file) {
    $name      = basename($file, '.php');
    $migration = require $file;

    if (is_array($migration) === false || is_callable($migration['run'] ?? null) === false) {
        fwrite(STDERR, "\n{$name}: must return ['description' => string, 'run' => callable]\n");
        $failed++;
        continue;
    }

    echo "\n[{$name}] " . ($migration['description'] ?? '') . "\n";

    try {
        $changed = (int)($migration['run'])($kirby, $dryRun);
        $total  += $changed;
        echo '  ' . ($dryRun ? 'would change' : 'changed') . " {$changed} page(s)\n";
    } catch (Throwable $e) {
        $failed++;
        echo "  ERROR: {$e->getMessage()}\n";
    }
}

echo "\n" . ($dryRun ? 'Would change' : 'Changed') . " {$total} page(s) in total, {$failed} failed migration(s).\n";

exit($failed > 0 ? 1 : 0);
