<?php

namespace Eclypsys\MenuSpecial;

use Kirby\Data\Data;
use Kirby\Exception\NotFoundException;
use Eclypsys\BaseClass;
use Eclypsys\MenuSpecial;

class DishSpecial extends BaseClass
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
    public static function add(string $pageId, array $input, string $target = 'dishes'): bool
    {
        $id = uuid();
        $menu = MenuSpecial::list();

        $dish = [
            "id" => $id,
            "name1" => $input["name1"] ?? "",
            "description1" => $input["description1"] ?? "",
            "option" => $input["option"] ?? [],
            "name2" => $input["name2"] ?? "",
            "description2" => $input["description2"] ?? "",
        ];

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                if (!isset($page[$target])) {
                    $page[$target] = [];
                }
                $page[$target][] = $dish;
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
    public static function update(string $id, array $input, string $pageId = '', string $target = 'dishes'): bool
    {
        if ($pageId === "") {
            return false;
        }

        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                if (!isset($page[$target])) continue;
                foreach ($page[$target] as &$dish) {
                    if ($dish["id"] === $id) {
                        $dish['name1'] = $input['name1'] ?? $dish['name1'];
                        $dish['description1'] = $input['description1'] ?? $dish['description1'];
                        $dish['option'] = $input['option'] ?? $dish['option'];
                        $dish['name2'] = $input['name2'] ?? $dish['name2'];
                        $dish['description2'] = $input['description2'] ?? $dish['description2'];
                        break;
                    }
                }
            }
        }

        return Data::write(static::file(), $menu);
    }

    public static function delete(string $id, string $pageId = "", string $target = 'dishes'): bool
    {
        if ($pageId === "") {
            return false;
        }

        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                if (!isset($page[$target])) continue;
                foreach ($page[$target] as $key => $dish) {
                    if ($dish["id"] === $id) {
                        unset($page[$target][$key]);
                        $page[$target] = array_values($page[$target]);
                        return Data::write(static::file(), $menu);
                    }
                }
            }
        }

        return Data::write(static::file(), $menu);
    }

    public static function find(string $id, string $pageId = '', string $target = 'dishes'): array
    {
        $menu = MenuSpecial::list();

        foreach ($menu['pages'] as &$page) {
            if ($page['id'] == $pageId) {
                if (!isset($page[$target])) continue;
                foreach ($page[$target] as $dish) {
                    if ($dish["id"] === $id) {
                        return $dish;
                    }
                }
            }
        }

        throw new NotFoundException("The item could not be found");
    }

}
