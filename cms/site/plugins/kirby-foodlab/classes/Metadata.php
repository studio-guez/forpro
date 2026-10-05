<?php

namespace Eclypsys\Menu;

use Kirby\Data\Data;
use Eclypsys\BaseClass;

class Metadata extends BaseClass
{
    const FILENAME = "metadata.json";

    public static function addOrUpdate(
        string $category,
        string $key,
        mixed $value
    ): bool {
        return self::modify(function (array $metadata) use (
            $category,
            $key,
            $value
        ) {
            $metadata[$category][$key] = $value;
            return $metadata;
        });
    }

    public static function get(string $category, mixed $key): mixed
    {
        $metadata = self::list();
        return $metadata[$category][$key] ?? "";
    }

    public static function updown(string $category, string $direction): bool
    {
        return self::modify(function (array $metadata) use (
            $category,
            $direction
        ) {
            $level = $metadata[$category]["level"] ?? 0;

            if ($direction === "down" && $level === 0) {
                $level = 0;
            } elseif ($direction === "down") {
                $level--;
            } else {
                $level++;
            }

            $metadata[$category]["level"] = $level;
            return $metadata;
        });
    }

    /**
     * Every Panel setting shares this one file, and several requests can edit
     * it at once. The read-modify-write is serialized on a lock file, and the
     * result is renamed into place so lock-free readers never see a truncated
     * file: an empty read decodes to [] and would be written back, wiping
     * every other setting.
     */
    private static function modify(callable $change): bool
    {
        $lock = fopen(static::file() . ".lock", "c");

        if ($lock === false) {
            return false;
        }

        flock($lock, LOCK_EX);

        try {
            $tmp = static::file() . ".tmp";
            Data::write($tmp, $change(self::list()), "json");
            return rename($tmp, static::file());
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
