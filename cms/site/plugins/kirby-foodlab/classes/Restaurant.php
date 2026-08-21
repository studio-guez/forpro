<?php

namespace Eclypsys;

use Kirby\Data\Json;
use Kirby\Exception\Exception;
use Kirby\Exception\NotFoundException;
use Kirby\Exception\PermissionException;
use Kirby\Filesystem\F;
use Kirby\Http\Response;

/**
 * Content of the FoodLab restaurant website.
 *
 * Everything lives inside the plugin (data/restaurant.json and
 * data/restaurant-media) instead of content/site.txt, so the restaurant
 * frontend can be synced and deployed independently from the ForPro site.
 */
class Restaurant
{
    /** Public path the uploaded media are served from */
    public const MEDIA_PATH = "api/restaurant/media";

    private const MAX_UPLOAD_SIZE = 10 * 1024 * 1024;

    private const ALLOWED_MIMES = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/gif" => "gif",
        "image/webp" => "webp",
        "image/svg+xml" => "svg",
    ];

    private const SERVABLE_EXTENSIONS = [
        "jpg",
        "jpeg",
        "png",
        "gif",
        "webp",
        "svg",
        "pdf",
    ];

    public static function file(): string
    {
        return __DIR__ . "/../data/restaurant.json";
    }

    public static function mediaDir(): string
    {
        return __DIR__ . "/../data/restaurant-media";
    }

    /**
     * This content used to be a tab of the site blueprint, so editing it stays
     * behind the very same permissions the site view requires.
     */
    public static function canEdit(): bool
    {
        $permissions = kirby()->user()?->role()->permissions();

        return $permissions?->for("access", "site") === true &&
            $permissions->for("site", "update") === true;
    }

    public static function requireEditPermission(): void
    {
        if (static::canEdit() === false) {
            throw new PermissionException(
                message: "You are not allowed to edit the restaurant content"
            );
        }
    }

    /**
     * Raw stored content, as edited in the panel
     */
    public static function get(): array
    {
        if (F::exists(static::file()) === false) {
            return [];
        }

        return Json::read(static::file());
    }

    /**
     * Stores the values of the panel form. Only fields defined in
     * fields/restaurant.php are kept.
     */
    public static function save(array $values): array
    {
        $data = static::get();

        foreach (static::fields() as $name => $field) {
            if (in_array($field["type"], ["headline", "line"], true)) {
                continue;
            }

            if (array_key_exists($name, $values) === false) {
                continue;
            }

            $data[$name] = $values[$name];
        }

        Json::write(static::file(), $data);

        return $data;
    }

    public static function fields(): array
    {
        return require __DIR__ . "/../fields/restaurant.php";
    }

    /**
     * Stores an uploaded image and returns its filename and public url
     */
    public static function upload(
        string $originalName,
        string $base64,
        string $mime
    ): array {
        if (isset(static::ALLOWED_MIMES[$mime]) === false) {
            throw new Exception(message: "Type de fichier non autorisé");
        }

        $binary = base64_decode($base64, true);

        if ($binary === false) {
            throw new Exception(message: "Données invalides");
        }

        if (strlen($binary) > static::MAX_UPLOAD_SIZE) {
            throw new Exception(message: "Fichier trop volumineux (max 10 Mo)");
        }

        $extension = static::ALLOWED_MIMES[$mime];
        $name = F::safeName(pathinfo($originalName, PATHINFO_FILENAME));
        $filename =
            $name . "-" . bin2hex(random_bytes(4)) . "." . $extension;
        $path = static::mediaDir() . "/" . $filename;

        F::write($path, $binary);

        // same downscale/CMYK handling the image-guard plugin applies to
        // files uploaded through Kirby
        if ($extension !== "svg" && class_exists("ImageGuard") === true) {
            \ImageGuard::process($path);
        }

        return [
            "filename" => $filename,
            "url" => static::mediaUrl($filename),
        ];
    }

    /**
     * Serves a media file from the plugin data folder
     */
    public static function serve(string $filename): Response
    {
        $filename = basename($filename);
        $path = static::mediaDir() . "/" . $filename;

        if (
            in_array(
                strtolower(F::extension($filename)),
                static::SERVABLE_EXTENSIONS,
                true
            ) === false ||
            F::exists($path, static::mediaDir()) === false
        ) {
            throw new NotFoundException(message: "Media not found");
        }

        return new Response(F::read($path), F::mime($path), 200, [
            "Content-Disposition" => 'inline; filename="' . $filename . '"',
            "Content-Security-Policy" => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
            "X-Content-Type-Options" => "nosniff",
            "Cache-Control" => "public, max-age=3600",
        ]);
    }

    /**
     * Stores the published menu PDF and links it from the two buttons that
     * point to the menu (FoodLab card in the "Le Lab" section and the footer)
     */
    public static function publishMenuPdf(
        string $filename,
        string $contents
    ): string {
        $filename = basename($filename);

        if (F::write(static::mediaDir() . "/" . $filename, $contents) === false) {
            throw new Exception(message: "Failed to write PDF file to disk.");
        }

        $data = static::get();
        $path = "/" . static::MEDIA_PATH . "/" . $filename;

        foreach (["btnLab", "btnFooter1"] as $key) {
            $button = $data[$key] ?? [];
            $button["link"] = $path;
            $button["target"] = true;
            $data[$key] = $button;
        }

        $data["menuPdf"] = $filename;

        Json::write(static::file(), $data);

        return url(ltrim($path, "/"));
    }

    public static function mediaUrl(?string $filename): string
    {
        if (empty($filename) === true) {
            return "";
        }

        return url(static::MEDIA_PATH . "/" . basename($filename));
    }

    /**
     * Content of the restaurant frontend, as consumed by the SvelteKit app
     */
    public static function toApi(): array
    {
        $data = static::get();

        $json = [];

        $json["banner_info"] = static::value($data, "banner_info");

        $json["menu"] = [
            "baseline" => static::value($data, "headline"),
        ];

        foreach (static::rows($data, "menu") as $element) {
            $json["menu"]["content"][] = [
                "text" => $element["text"] ?? "",
                "link" => static::link($element["link"] ?? ""),
            ];
        }

        $json["hero"] = [
            "btn1" => static::button($data, "btnHero1"),
            "pictureURL1" => static::mediaUrl(static::value($data, "picHero1")),
            "pictureURL2" => static::mediaUrl(static::value($data, "picHero2")),
            "pictureURL3" => static::mediaUrl(static::value($data, "picHero3")),
            "text" => static::text($data, "textHero1"),
        ];

        $json["food"] = [
            "title" => static::value($data, "titleFood"),
            "text" => static::text($data, "textFood"),
            "pictureURL" => static::mediaUrl(static::value($data, "fileFood1")),
            "btn" => static::button($data, "btnFood"),
        ];

        $json["lab"] = [
            "title" => static::value($data, "titleLab"),
            "text" => static::text($data, "textLab"),
            "pictureURL" => static::mediaUrl(static::value($data, "fileLab1")),
            "btn" => static::button($data, "btnLab"),
        ];

        $json["highlight"] = [
            "pictureURL" => static::mediaUrl(static::value($data, "picture1")),
        ];

        $json["formation"] = [
            "title" => static::value($data, "titleFormation"),
            "text" => static::text($data, "textFormation"),
            "pictureURL" => static::mediaUrl(
                static::value($data, "fileFormation")
            ),
            "btn" => static::button($data, "btnFormation"),
        ];

        $json["univers"] = [
            "title" => static::value($data, "titleUnivers"),
            "subtitle" => static::value($data, "subtitleUnivers"),
            "blogTitle1" => static::value($data, "blogUniversTitle1"),
            "blogPictureUrl1" => static::mediaUrl(
                static::value($data, "blogUniversFil1")
            ),
            "blogText1" => static::text($data, "blogUniversText1"),
            "blogTitle2" => static::value($data, "blogUniversTitle2"),
            "blogPictureUrl2" => static::mediaUrl(
                static::value($data, "blogUniversFile2")
            ),
            "blogText2" => static::text($data, "blogUniversText2"),
        ];

        $json["values"] = [
            "title" => static::value($data, "titleValues"),
            "text" => static::value($data, "textValues"),
        ];

        foreach (static::rows($data, "lstValues") as $value) {
            $json["values"]["list"][] = [
                "title" => $value["title"] ?? "",
                "icon" => static::mediaUrl($value["icon"] ?? ""),
            ];
        }

        $json["footer"] = [
            "Headline" => static::value($data, "footerHeadline"),
            "text1" => static::text($data, "textFooter1"),
            "text2" => static::text($data, "textFooter2"),
            "text3" => static::text($data, "textFooter3"),
            "btn1" => static::button($data, "btnFooter1"),
            "btn2" => static::button($data, "btnFooter2"),
        ];

        return $json;
    }

    private static function value(array $data, string $key): string
    {
        $value = $data[$key] ?? "";

        return is_string($value) === true ? $value : "";
    }

    /**
     * Renders a textarea value with KirbyText, like the site fields did
     */
    private static function text(array $data, string $key): string
    {
        $value = static::value($data, $key);

        return $value === "" ? "" : (string) kirbytext($value);
    }

    private static function rows(array $data, string $key): array
    {
        $rows = $data[$key] ?? [];

        return is_array($rows) === true ? $rows : [];
    }

    private static function button(array $data, string $key): array
    {
        $button = $data[$key] ?? [];
        $button = is_array($button) === true ? $button : [];

        return [
            "link" => static::link($button["link"] ?? ""),
            "text" => $button["linkText"] ?? "",
            "target" => filter_var(
                $button["target"] ?? false,
                FILTER_VALIDATE_BOOLEAN
            ),
        ];
    }

    /**
     * Turns internal paths (the published menu PDF) into absolute urls and
     * leaves external links and anchors untouched
     */
    private static function link(string $value): string
    {
        if ($value === "") {
            return "";
        }

        if (str_starts_with($value, "/") === true) {
            return url(ltrim($value, "/"));
        }

        return $value;
    }
}
