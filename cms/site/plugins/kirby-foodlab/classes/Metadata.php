<?php

namespace MediumSans\Menu;

use Kirby\Data\Data;
use MediumSans\BaseClass;

class Metadata extends BaseClass
{
    const FILENAME = "metadata.json";

    public static function addOrUpdate(string $category, string $key, string $value): bool
    {
        $metadata = self::list();
        $metadata[$category][$key] = $value;

        return Data::write(static::file(), $metadata);
    }

    public static function get(string $category, string $key): string
    {
        $metadata = self::list();
        return $metadata[$category][$key] ?? "";
    }
}
