<?php

namespace Eclypsys\MenuSpecial;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;
use Eclypsys\BaseClass;
use Eclypsys\MenuSpecial;

class WineSpecial extends BaseClass
{
    const FILENAME = "menu-special.json";

    /**
     * Creates a new dish with the given $input
     * data and adds it to the json file
     *
     * @param string $pageId
     * @param array $input
     * @return bool
     */
    public static function add(string $pageId, array $input): bool
    {
        $id = uuid();
        $menu = MenuSpecial::list();

        $wine = [
            "id" => $id,
            "name" => $input["name"] ?? "",
            "description" => $input["description"] ?? "",
            "domain" => $input["domain"] ?? "",
            "mill" => $input["mill"] ?? "",
        ];

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                $page['wines'][] = $wine;
                break;
            }
        }

        return Data::write(static::file(), $menu);
    }

    /**
     * Updates a menu by id with the given input
     * It throws an exception in case of validation issues
     *
     * @param string $id
     * @param array $input
     * @param string $pageId
     * @return boolean
     */
    public static function update(string $id, array $input, string $pageId = ''): bool
    {
        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                foreach ($page['wines'] as &$wine) {
                    if ($wine["id"] === $id) {
                        $wine['name'] = $input['name'] ?? $wine['name'];
                        $wine['description'] = $input['description'] ?? $wine['description'];
                        $wine['domain'] = $input['domain'] ?? $wine['domain'];
                        $wine['mill'] = $input['mill'] ?? $wine['mill'];
                        break;
                    }
                }
            }
        }

        return Data::write(static::file(), $menu);
    }

    public static function delete(string $id, string $pageId = ""): bool
    {
        if ($pageId === "") {
            return false;
        }

        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                foreach ($page['wines'] as $key => $wine) {
                    if ($wine["id"] === $id) {
                        unset($page['wines'][$key]);
                        $page['wines'] = array_values($page['wines']);
                        return Data::write(static::file(), $menu);
                    }
                }
            }
        }

        return Data::write(static::file(), $menu);
    }

    public static function find(string $id, string $pageId = ''): array
    {
        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                foreach ($page['wines'] as $wine) {
                    if ($wine["id"] === $id) {
                        return $wine;
                    }
                }
            }
        }

        throw new NotFoundException("The item could not be found");
    }
}
