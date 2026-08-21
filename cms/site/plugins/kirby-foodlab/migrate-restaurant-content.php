<?php

/**
 * migrate-restaurant-content.php
 *
 * One-off migration: moves the FoodLab restaurant content out of
 * `content/site.txt` (where it used to live as a tab of the site blueprint)
 * into this plugin, so the restaurant frontend can be synced and deployed
 * independently from the rest of the ForPro site.
 *
 * It does three things:
 *   1. exports the restaurant fields to `data/restaurant.json`
 *   2. copies every referenced image/PDF to `data/restaurant-media/`
 *   3. removes the migrated keys from `content/site.txt`
 *
 * The originals are left untouched in `content/` - they are simply not
 * referenced anymore and can be deleted from the Panel afterwards.
 *
 * Usage (run from the project root, inside the container):
 *   php site/plugins/kirby-foodlab/migrate-restaurant-content.php --dry-run
 *   php site/plugins/kirby-foodlab/migrate-restaurant-content.php
 *   php site/plugins/kirby-foodlab/migrate-restaurant-content.php --force
 *
 * @author    Octoplus Solutions
 * @license   Proprietary - All rights reserved. Not free for use.
 */

use Eclypsys\Restaurant;
use Kirby\Cms\App;
use Kirby\Data\Json;
use Kirby\Filesystem\Dir;

require_once __DIR__ . '/../../../kirby/bootstrap.php';

$kirby = new App(['roots' => ['index' => realpath(__DIR__ . '/../../..')]]);
$site  = $kirby->site();

$dryRun = in_array('--dry-run', $argv, true);
$force  = in_array('--force', $argv, true);

$dataFile = Restaurant::file();
$mediaDir = Restaurant::mediaDir();

if (file_exists($dataFile) === true && $force === false && $dryRun === false) {
    fwrite(STDERR, "{$dataFile} already exists - rerun with --force to overwrite\n");
    exit(1);
}

// fields/restaurant.php drives both the export and the site.txt cleanup
$fields = Restaurant::fields();
$layout = ['headline', 'line'];

$sourceKeys = array_keys(array_filter(
    $fields,
    fn($field) => in_array($field['type'], $layout, true) === false
));

if (count(array_filter($sourceKeys, fn($key) => $site->$key()->isNotEmpty())) === 0) {
    fwrite(STDERR, "no restaurant content found in site.txt - nothing to migrate\n");
    exit(1);
}

/* -------------------------------------------------------------------------
   1. + 2. export the fields and collect the media
   ------------------------------------------------------------------------- */

$copied = [];

$copy = function ($file) use ($mediaDir, $dryRun, &$copied) {
    if ($file === null) {
        return '';
    }

    $filename = $file->filename();

    if (isset($copied[$filename]) === false) {
        if ($dryRun === false) {
            if (copy($file->root(), $mediaDir . '/' . $filename) === false) {
                fwrite(STDERR, "ERROR: failed to copy {$filename} - aborting\n");
                exit(1);
            }
        }

        $copied[$filename] = true;
        echo "  copy   {$filename}\n";
    }

    return $filename;
};

// a link either points at an uploaded file or is a plain url/anchor
$link = function ($field) use ($copy) {
    if ($file = $field->toFile()) {
        return '/' . Restaurant::MEDIA_PATH . '/' . $copy($file);
    }

    return $field->value() ?? '';
};

$button = fn($object) => [
    'link'     => $link($object->link()),
    'linkText' => $object->linkText()->value() ?? '',
    'target'   => $object->target()->toBool(),
];

$export = function (string $type, $field) use ($copy, $button) {
    return match ($type) {
        'restaurantimage' => $copy($field->toFile()),
        'object'          => $button($field->toObject()),
        'toggle'          => $field->toBool(),
        default           => $field->value() ?? '',
    };
};

if ($dryRun === false) {
    Dir::make($mediaDir);
}

$data = [];

foreach ($fields as $name => $field) {
    if (in_array($field['type'], $layout, true) === true) {
        continue;
    }

    if ($field['type'] === 'structure') {
        $data[$name] = [];

        foreach ($site->$name()->toStructure() as $row) {
            $entry = [];

            foreach ($field['fields'] as $subName => $subField) {
                $entry[$subName] = $export($subField['type'], $row->$subName());
            }

            $data[$name][] = $entry;
        }

        continue;
    }

    $data[$name] = $export($field['type'], $site->$name());
}

// the published menu PDF is tracked separately so the panel can replace it
if ($pdf = $site->btnLab()->toObject()->link()->toFile()) {
    $data['menuPdf'] = $pdf->filename();
}

if ($dryRun === false) {
    $result = Json::write($dataFile, $data);

    if ($result === false) {
        fwrite(STDERR, "ERROR: failed to write {$dataFile} - aborting before cleanup\n");
        exit(1);
    }
}

echo "\n" . count($copied) . " media files -> {$mediaDir}\n";
echo count($data) . " keys -> {$dataFile}\n";

/* -------------------------------------------------------------------------
   3. strip the migrated keys from site.txt
   ------------------------------------------------------------------------- */

// Kirby lowercases keys and writes underscores as dashes
$remove = array_map(
    fn($key) => str_replace('_', '-', strtolower($key)),
    [...$sourceKeys, 'menuPdf']
);

// site.txt is rewritten block by block instead of through Data::write, so the
// keys we keep are left byte for byte as they are
foreach (glob($kirby->root('content') . '/site*.txt') as $path) {
    $blocks = preg_split('/\n----\n/', file_get_contents($path));
    $keep   = [];
    $drop   = [];

    foreach ($blocks as $block) {
        $key = strtolower(trim(explode(':', ltrim($block), 2)[0]));

        if (in_array($key, $remove, true) === true) {
            $drop[] = $key;
            continue;
        }

        $keep[] = $block;
    }

    $backup = sys_get_temp_dir() . '/' . basename($path) . '.bak-' . date('Ymd-His');

    echo "\n" . basename($path) . ': ' . count($drop) . ' keys removed, '
        . count($keep) . " kept\n";
    echo "  dropped: " . implode(', ', $drop) . "\n";

    if ($dryRun === false) {
        if (copy($path, $backup) === false) {
            fwrite(STDERR, "ERROR: failed to create backup {$backup} - aborting\n");
            exit(1);
        }

        $tmp = $path . '.tmp-' . getmypid();

        if (file_put_contents($tmp, implode("\n----\n", $keep)) === false) {
            unlink($tmp);
            fwrite(STDERR, "ERROR: failed to write {$tmp} - aborting (backup at {$backup})\n");
            exit(1);
        }

        if (rename($tmp, $path) === false) {
            unlink($tmp);
            fwrite(STDERR, "ERROR: failed to rename {$tmp} to {$path} - aborting (backup at {$backup})\n");
            exit(1);
        }

        echo "  backup:  {$backup}\n";
    }
}

if ($dryRun === true) {
    echo "\ndry run - nothing was written\n";
}
