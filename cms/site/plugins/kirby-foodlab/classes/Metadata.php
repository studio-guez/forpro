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
        $metadata = self::list();
        $metadata[$category][$key] = $value;

        return Data::write(static::file(), $metadata);
    }

    public static function get(string $category, mixed $key): mixed
    {
        $metadata = self::list();
        return $metadata[$category][$key] ?? "";
    }

    public static function updown(string $category, string $direction): bool
    {
        $metadata = self::list();
        $level = $metadata[$category]["level"] ?? 0;

        if ($direction === "down" && $level === 0) {
            $level = 0;
        } elseif ($direction === "down") {
            $level--;
        } else {
            $level++;
        }

        $metadata[$category]["level"] = $level;

        return Data::write(static::file(), $metadata);
    }
}
