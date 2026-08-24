<?php

/**
 * warm-thumbs.php
 *
 * Pre-generates every thumbnail the frontends ask for, instead of waiting for
 * the first visitor to trigger it. Kirby has no built-in command for this:
 * a rendition is written by the `file::version` component the moment
 * `resize()` / `crop()` / `srcset()` is called, so the only way to force it is
 * to call those methods on every file.
 *
 * The renditions below must mirror the ones emitted in `utils/traits/`
 * (`UtilsMedia::getJsonEncodeImageData()`, `UtilsMedia::getFaviconData()`,
 * `UtilsSearch::getSearchCover()`) - a size that is warmed here but not
 * requested there is wasted disk, and vice versa.
 *
 * Usage (run from the CMS root, inside the container, as www-data):
 *   php utils/warm-thumbs.php --dry-run   # list the files that would be processed
 *   php utils/warm-thumbs.php             # generate the missing thumbs
 *   php utils/warm-thumbs.php --force     # regenerate from scratch (clears media/)
 *
 * Existing thumbs are skipped by Kirby itself, so a plain run is cheap and
 * safe to repeat.
 *
 * @author    Octoplus Solutions
 * @license   Proprietary - All rights reserved. Not free for use.
 */

require_once __DIR__ . '/../kirby/bootstrap.php';

$root    = dirname(__DIR__);
$dryRun  = in_array('--dry-run', $argv, true);
$force   = in_array('--force', $argv, true);

$kirby = new Kirby\Cms\App(['roots' => ['index' => $root]]);

if ($force && $dryRun === false) {
    foreach (['pages', 'site'] as $dir) {
        Kirby\Filesystem\Dir::remove($kirby->root('media') . '/' . $dir);
    }
    echo "Cleared media/pages and media/site.\n";
}

$site        = $kirby->site();
$searchSizes = [240, 480];
$faviconSizes = option('favicon.resize', [16, 32, 48, 180, 192, 512]);

/** Every page (drafts included) plus the site itself, so no file is missed. */
$parents = [$site, ...$site->index(true)->values()];

$scanned  = 0;
$skipped  = 0;
$failed   = 0;
$before   = countMediaFiles($kirby->root('media'));

foreach ($parents as $parent) {
    foreach ($parent->files() as $file) {
        if ($file->type() !== 'image' || $file->isResizable() === false) {
            $skipped++;
            continue;
        }

        $scanned++;

        // Printed before the work, not after: an oversized original can hit
        // PHP's memory limit, which kills the process without a catchable error.
        echo str_pad((string) $scanned, 5, ' ', STR_PAD_LEFT) . "  {$file->id()}";

        if ($dryRun) {
            echo "\n";
            continue;
        }

        try {
            $file->resize(1920);
            $file->srcset('default');

            foreach ($searchSizes as $size) {
                $file->crop($size, $size);
            }

            if ($parent === $site && $file->extension() === 'png') {
                foreach ($faviconSizes as $size) {
                    $file->resize($size);
                }
            }

            echo "  OK\n";
        } catch (Throwable $e) {
            $failed++;
            echo "  ERROR: {$e->getMessage()}\n";
        }
    }
}

$generated = countMediaFiles($kirby->root('media')) - $before;

echo "\n" . ($dryRun ? 'Would process' : 'Processed') . " {$scanned} image(s)"
    . ", skipped {$skipped} non-resizable file(s)"
    . ($dryRun ? ".\n" : ", generated {$generated} new file(s) in media/, {$failed} error(s).\n");

if ($failed > 0) {
    echo "\nFailures are usually oversized or CMYK originals - fix them with:\n";
    echo "  php site/plugins/image-guard/fix-large-images.php\n";
    exit(1);
}

function countMediaFiles(string $mediaRoot): int
{
    if (is_dir($mediaRoot) === false) {
        return 0;
    }

    $count = 0;

    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($mediaRoot, FilesystemIterator::SKIP_DOTS)
    ) as $entry) {
        if ($entry->isFile()) {
            $count++;
        }
    }

    return $count;
}
