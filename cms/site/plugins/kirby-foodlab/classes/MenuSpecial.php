<?php

namespace Eclypsys;

use Kirby\Data\Data;

class MenuSpecial extends BaseClass
{
    const FILENAME = "menu-special.json";

    /**
     * Reads the menu data and normalizes it with defaults
     * for backward compatibility with existing JSON files.
     */
    public static function list(): array
    {
        if (!file_exists(static::file())) {
            return [
                'id'              => '',
                'pages'           => [],
                'qrUrl'           => '',
                'textAboveQr'     => '',
                'showPartner'     => false,
                'textPartner'     => '',
                'titlePartner'    => '',
                'subtitlePartner' => '',
                'partnerLogo'     => '',
                'partnerLogoWidth'  => '',
                'partnerLogoHeight' => '',
                'partnerLogoTop'    => '',
                'partnerLogoLeft'   => '',
                'partnerLogoRight'  => '',
                'partnerLogoBottom' => '',
                'showDishes'      => true,
                'showWines'       => true,
            ];
        }

        $menu = parent::list();

        // Migrate old textInfo → textAboveQr
        if (isset($menu['textInfo']) && !isset($menu['textAboveQr'])) {
            $menu['textAboveQr'] = $menu['textInfo'];
            unset($menu['textInfo']);
        }

        // Top-level defaults for new fields
        $menu['qrUrl']         = $menu['qrUrl'] ?? "";
        $menu['textAboveQr']   = $menu['textAboveQr'] ?? "";
        $menu['showPartner']   = $menu['showPartner'] ?? false;

        // Normalize each page with new fields
        if (isset($menu['pages']) && is_array($menu['pages'])) {
            foreach ($menu['pages'] as &$page) {
                $page['layout']       = $page['layout'] ?? 'wines-dishes';
                $page['dishes2']      = $page['dishes2'] ?? [];
                $page['dishesTitle2'] = $page['dishesTitle2'] ?? 'Plats 2';
                $page['showDishes2']  = $page['showDishes2'] ?? true;
                $page['showWines']    = $page['showWines'] ?? true;
                $page['showDishes']   = $page['showDishes'] ?? true;
            }
            unset($page);
        }

        return $menu;
    }

    public static function get(bool $renderWithAssets): array
    {
        $menu = self::list();
        $menu['renderWithAssets'] = $renderWithAssets;

        return [
            "menu" => $menu,
        ];
    }

    /**
     * Creates a new menu with the given $input
     * data and adds it to the json file
     *
     * @param array $input
     * @return bool
     */
    public static function create(array $input): bool
    {
        $id = uuid();

        $menu = [
            "id"            => $id,
            "pages"         => $input["pages"] ?? [],
            "qrUrl"         => $input["qrUrl"] ?? "",
            "textAboveQr"   => $input["textAboveQr"] ?? "",
            "showPartner"   => $input["showPartner"] ?? false,
            "textPartner"   => $input["textPartner"] ?? "",
            "titlePartner"  => $input["titlePartner"] ?? "",
            "subtitlePartner" => $input["subtitlePartner"] ?? "",
            "partnerLogo"   => $input["partnerLogo"] ?? "",
            "partnerLogoWidth" => $input["partnerLogoWidth"] ?? "",
            "partnerLogoHeight" => $input["partnerLogoHeight"] ?? "",
            "partnerLogoTop" => $input["partnerLogoTop"] ?? "",
            "partnerLogoLeft" => $input["partnerLogoLeft"] ?? "",
            "partnerLogoRight" => $input["partnerLogoRight"] ?? "",
            "partnerLogoBottom" => $input["partnerLogoBottom"] ?? "",
            "showDishes" => $input["showDishes"] ?? true,
            "showWines" => $input["showWines"] ?? true,
        ];

        return Data::write(static::file(), $menu);
    }
}
