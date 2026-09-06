<?php

namespace Eclypsys;

use Closure;
use Kirby\Data\Json;
use Kirby\Exception\Exception;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\NotFoundException;
use Kirby\Exception\PermissionException;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use Kirby\Form\Form;
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

    /**
     * Api path the panel view talks to. It plays the role a model api path
     * (`pages/…`) plays for a normal panel view: the content changes
     * endpoints live under `<API_PATH>/changes/…` and the field endpoints
     * under `<API_PATH>/fields/…`.
     */
    public const API_PATH = "restaurant";

    private const MAX_UPLOAD_SIZE = 20 * 1024 * 1024;

    private const IMAGE_EXTENSIONS = [
        "jpg",
        "jpeg",
        "png",
        "gif",
        "webp",
        "svg",
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
     * The unsaved draft of the panel form, mirroring Kirby's `changes`
     * content version. Absent as long as there is nothing to save.
     */
    public static function changesFile(): string
    {
        return __DIR__ . "/../data/restaurant.changes.json";
    }

    public static function changes(): array|null
    {
        if (F::exists(static::changesFile()) === false) {
            return null;
        }

        return Json::read(static::changesFile());
    }

    /**
     * The `latest` / `changes` versions the panel content state works with.
     * Both are form values, so `$panel.content.diff()` compares like for like.
     */
    public static function versions(): array
    {
        $latest = static::get();
        $changes = static::changes();

        return [
            "latest" => static::form($latest)->toFormValues(),
            // a draft is always a complete version, but merging keeps the form
            // whole should a field ever be added to fields/restaurant.php
            // while an editor has unsaved changes
            "changes" => static::form(
                $changes === null ? $latest : [...$latest, ...$changes]
            )->toFormValues(),
        ];
    }

    /**
     * Keeps the draft of the panel form (autosave)
     */
    public static function saveChanges(array $values): void
    {
        static::modify(
            static::changesFile(),
            fn(array $data): array => static::toStoredValues(
                $values,
                $data === [] ? static::get() : $data
            )
        );
    }

    /**
     * Writes the draft to the stored content and drops it
     */
    public static function publish(array $values): array
    {
        static::form($values)->validate();

        $data = static::modify(
            static::file(),
            fn(array $data): array => static::toStoredValues($values, $data)
        );

        F::remove(static::changesFile());

        return $data;
    }

    public static function discard(): void
    {
        F::remove(static::changesFile());
    }

    /**
     * Panel form values -> stored content. `Form` lowercases every field
     * name, including nested structure/object subfield names, so the JSON
     * keys are the lowercased field names of fields/restaurant.php (e.g.
     * `btnHero1` is stored as `btnhero1`, `linkText` as `linktext`).
     *
     * Only the submitted fields are written. `Form` fills the ones that are
     * missing with their empty value, so without this a truncated request
     * would blank everything it did not carry.
     */
    private static function toStoredValues(
        array $values,
        array $data = []
    ): array {
        if ($values === []) {
            throw new InvalidArgumentException(
                message: "No restaurant content was submitted"
            );
        }

        $values = array_change_key_case($values, CASE_LOWER);
        $stored = array_intersect_key(
            static::form($values)->toStoredValues(),
            $values
        );

        return [...$data, ...$stored];
    }

    /**
     * Reads the current content of $file (or an empty array if it does not
     * exist yet), lets $callback transform it, and writes the result back —
     * the whole read-modify-write cycle happens under one exclusive lock, so
     * concurrent requests cannot race each other or clobber one another's
     * writes.
     */
    private static function modify(string $file, Closure $callback): array
    {
        $lock = fopen($file . ".lock", "c");

        if ($lock === false || flock($lock, LOCK_EX) === false) {
            throw new Exception(message: "Failed to acquire write lock.");
        }

        try {
            $data = F::exists($file) === true ? Json::read($file) : [];
            $data = $callback($data);

            if (Json::write($file, $data) === false) {
                throw new Exception(message: "Failed to write " . basename($file) . ".");
            }

            return $data;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * The form of the restaurant content, filled with the given values.
     *
     * The site is passed as the model so field types that expect one (the
     * link and textarea previews) keep working; nothing is ever written to it.
     *
     * `passthrough: false` keeps keys that are not fields of
     * fields/restaurant.php out of the form (and therefore out of the JSON).
     */
    public static function form(array $values = []): Form
    {
        $form = new Form(fields: static::fields(), model: kirby()->site());

        return $form->fill(input: $values, passthrough: false);
    }

    /**
     * Props of every field, as `k-form` expects them. `endpoints` is what
     * `k-fields-section` adds client-side for a model.
     */
    public static function fieldProps(): array
    {
        $props = static::form()->toProps();

        foreach ($props as $name => $field) {
            $props[$name]["endpoints"] = [
                "field" => static::API_PATH . "/fields/" . $name,
                "model" => static::API_PATH,
            ];
        }

        return $props;
    }

    /**
     * `k-files-field` hands back an array of picker items; only the filename
     * of the first one is stored.
     */
    public static function toStoredMedia($value): string
    {
        if (is_string($value) === true) {
            return basename($value);
        }

        if (is_array($value) === false) {
            return "";
        }

        $first = reset($value);

        if (is_array($first) === false) {
            return is_string($first) === true ? basename($first) : "";
        }

        return basename($first["id"] ?? ($first["filename"] ?? ""));
    }

    /**
     * Stored filename -> the picker payload `k-files-field` expects
     */
    public static function toPickerValue($value): array
    {
        return array_values(
            array_filter([static::pickerData(static::toStoredMedia($value))])
        );
    }

    public static function fields(): array
    {
        return require __DIR__ . "/../fields/restaurant.php";
    }

    /**
     * Every file in the media library, oldest first (same order Kirby lists
     * the files of a page in its file picker)
     */
    public static function media(): array
    {
        $files = array_values(
            array_filter(
                Dir::files(static::mediaDir()),
                fn(string $filename): bool => in_array(
                    strtolower(F::extension($filename)),
                    static::SERVABLE_EXTENSIONS,
                    true
                )
            )
        );

        sort($files, SORT_NATURAL | SORT_FLAG_CASE);

        return $files;
    }

    /**
     * One entry of the media library, in the shape `k-collection` /
     * `k-files-dialog` expect (mirrors `$file->panel()->pickerData()`)
     */
    public static function pickerData(string $filename): array|null
    {
        $filename = basename($filename);

        if ($filename === "") {
            return null;
        }

        $path = static::mediaDir() . "/" . $filename;

        if (F::exists($path, static::mediaDir()) === false) {
            return null;
        }

        $extension = strtolower(F::extension($filename));
        $isImage = in_array($extension, static::IMAGE_EXTENSIONS, true);
        $url = static::mediaUrl($filename);

        return [
            "id" => $filename,
            "filename" => $filename,
            "extension" => $extension,
            "mime" => F::mime($path),
            "text" => $filename,
            "info" => F::niceSize($path),
            "link" => $url,
            "url" => $url,
            // what a media link field stores, kept relative so the content
            // survives a change of host
            "path" => "/" . static::MEDIA_PATH . "/" . $filename,
            "image" => [
                "back" => "pattern",
                "color" => $isImage === true ? "gray-500" : "red-400",
                "cover" => false,
                "icon" => $isImage === true ? "image" : "file-document",
                "src" => $isImage === true ? $url : null,
            ],
            "permissions" => ["delete" => false, "sort" => true],
            "sortable" => true,
            "type" => $isImage === true ? "image" : "document",
            // what the file button of the textarea toolbar inserts; the path
            // stays relative so the content survives a change of host
            "dragText" => $isImage === true
                ? "(image: /" . static::MEDIA_PATH . "/" . $filename . ")"
                : "(link: /" . static::MEDIA_PATH . "/" . $filename .
                    " text: " . $filename . ")",
        ];
    }

    /**
     * Paginated media library for the `k-files-dialog` picker
     */
    public static function picker(
        string|null $search = null,
        int $page = 1,
        int $limit = 20
    ): array {
        $files = static::media();

        if (empty($search) === false) {
            $files = array_values(
                array_filter(
                    $files,
                    fn(string $filename): bool => stripos(
                        $filename,
                        $search
                    ) !== false
                )
            );
        }

        $page = max(1, $page);

        return [
            "data" => array_values(
                array_filter(
                    array_map(
                        static::pickerData(...),
                        array_slice($files, ($page - 1) * $limit, $limit)
                    )
                )
            ),
            "pagination" => [
                "limit" => $limit,
                "page" => $page,
                "total" => count($files),
            ],
        ];
    }

    /**
     * Stores an uploaded file in the media library and returns its picker
     * payload. Called from the api route through `Api::upload()`, so chunked
     * uploads of large files are handled by Kirby.
     */
    public static function upload(string $source, string $filename): array
    {
        $extension = strtolower(F::extension($filename));

        if (in_array($extension, static::SERVABLE_EXTENSIONS, true) === false) {
            throw new InvalidArgumentException(
                message: "Type de fichier non autorisé"
            );
        }

        if (F::size($source) > static::MAX_UPLOAD_SIZE) {
            throw new InvalidArgumentException(
                message: "Fichier trop volumineux (max 20 Mo)"
            );
        }

        $dir = static::mediaDir();

        if (Dir::make($dir) === false) {
            throw new Exception(message: "Failed to create media directory.");
        }

        $name = F::safeName(F::name($filename));
        $filename = $name . "." . $extension;
        $index = 1;

        while (F::exists($dir . "/" . $filename, $dir) === true) {
            $filename = $name . "-" . $index++ . "." . $extension;
        }

        $path = $dir . "/" . $filename;

        if (F::move($source, $path) === false) {
            throw new Exception(message: "Failed to write media file to disk.");
        }

        // same downscale/CMYK handling the image-guard plugin applies to
        // files uploaded through Kirby
        if ($extension !== "svg" && class_exists("ImageGuard") === true) {
            \ImageGuard::process($path);
        }

        return static::pickerData($filename) ??
            throw new Exception(message: "Failed to read the uploaded file.");
    }

    /**
     * Serves a media file from the plugin data folder
     */
    public static function serve(string $filename): Response
    {
        $filename = basename($filename);
        $path = static::mediaDir() . "/" . $filename;
        $extension = strtolower(F::extension($filename));

        if (
            in_array($extension, static::SERVABLE_EXTENSIONS, true) === false ||
            F::exists($path, static::mediaDir()) === false
        ) {
            throw new NotFoundException(message: "Media not found");
        }

        $headers = [
            "Content-Disposition" => 'inline; filename="' . $filename . '"',
            "X-Content-Type-Options" => "nosniff",
            "Cache-Control" => "public, max-age=3600",
        ];

        // the sandboxed CSP is only meant to neutralise SVGs (the one type
        // here that can carry a script); applying it to every file breaks
        // PDFs in Chrome, which refuses to render a PDF served with a
        // sandbox CSP at all
        if ($extension === "svg") {
            $headers["Content-Security-Policy"] =
                "default-src 'none'; style-src 'unsafe-inline'; sandbox";
        }

        return new Response(F::read($path), F::mime($path), 200, $headers);
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

        $path = "/" . static::MEDIA_PATH . "/" . $filename;

        $data = static::modify(
            static::file(),
            function (array $data) use ($path, $filename): array {
                foreach (["btnlab", "btnfooter1"] as $key) {
                    $button = $data[$key] ?? [];
                    $button["link"] = $path;
                    $button["target"] = true;
                    $data[$key] = $button;
                }

                $data["menupdf"] = $filename;

                return $data;
            }
        );

        // an editor may have unsaved changes open; the published menu must
        // not be undone the next time they hit save
        if (F::exists(static::changesFile()) === true) {
            static::modify(
                static::changesFile(),
                function (array $changes) use ($data): array {
                    foreach (["btnlab", "btnfooter1"] as $key) {
                        $changes[$key] = $data[$key];
                    }

                    $changes["menupdf"] = $data["menupdf"];

                    return $changes;
                }
            );
        }

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

        $json["banner_info"] = static::absoluteMediaUrls(
            static::value($data, "banner_info")
        );

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
            "btn1" => static::button($data, "btnhero1"),
            "pictureURL1" => static::mediaUrl(static::value($data, "pichero1")),
            "pictureURL2" => static::mediaUrl(static::value($data, "pichero2")),
            "pictureURL3" => static::mediaUrl(static::value($data, "pichero3")),
            "text" => static::text($data, "texthero1"),
        ];

        $json["food"] = [
            "title" => static::value($data, "titlefood"),
            "text" => static::text($data, "textfood"),
            "pictureURL" => static::mediaUrl(static::value($data, "filefood1")),
            "btn" => static::button($data, "btnfood"),
        ];

        $json["lab"] = [
            "title" => static::value($data, "titlelab"),
            "text" => static::text($data, "textlab"),
            "pictureURL" => static::mediaUrl(static::value($data, "filelab1")),
            "btn" => static::button($data, "btnlab"),
        ];

        $json["highlight"] = [
            "pictureURL" => static::mediaUrl(static::value($data, "picture1")),
        ];

        $json["formation"] = [
            "title" => static::value($data, "titleformation"),
            "text" => static::text($data, "textformation"),
            "pictureURL" => static::mediaUrl(
                static::value($data, "fileformation")
            ),
            "btn" => static::button($data, "btnformation"),
        ];

        $json["univers"] = [
            "title" => static::value($data, "titleunivers"),
            "subtitle" => static::value($data, "subtitleunivers"),
            "blogTitle1" => static::value($data, "bloguniverstitle1"),
            "blogPictureUrl1" => static::mediaUrl(
                static::value($data, "bloguniversfil1")
            ),
            "blogText1" => static::text($data, "bloguniverstext1"),
            "blogTitle2" => static::value($data, "bloguniverstitle2"),
            "blogPictureUrl2" => static::mediaUrl(
                static::value($data, "bloguniversfile2")
            ),
            "blogText2" => static::text($data, "bloguniverstext2"),
            "popupMenuUrl" => static::mediaUrl(
                static::value($data, "popupmenupdf")
            ),
        ];

        $json["values"] = [
            "title" => static::value($data, "titlevalues"),
            "text" => static::value($data, "textvalues"),
        ];

        foreach (static::rows($data, "lstvalues") as $value) {
            $json["values"]["list"][] = [
                "title" => $value["title"] ?? "",
                "icon" => static::mediaUrl($value["icon"] ?? ""),
            ];
        }

        $json["footer"] = [
            "Headline" => static::value($data, "footerheadline"),
            "text1" => static::text($data, "textfooter1"),
            "text2" => static::text($data, "textfooter2"),
            "text3" => static::text($data, "textfooter3"),
            "btn1" => static::button($data, "btnfooter1"),
            "btn2" => static::button($data, "btnfooter2"),
        ];

        return $json;
    }

    private static function value(array $data, string $key): string
    {
        $value = $data[$key] ?? "";

        return is_string($value) === true ? $value : "";
    }

    /**
     * Renders a textarea value with KirbyText, like the site fields did.
     */
    private static function text(array $data, string $key): string
    {
        $value = static::value($data, $key);

        if ($value === "") {
            return "";
        }

        return static::absoluteMediaUrls((string) kirbytext($value));
    }

    /**
     * Media inserted through the file button of a textarea/writer toolbar is
     * stored as a root-relative path so the content stays portable between
     * environments; the frontend runs on another host, so it is made
     * absolute here.
     */
    private static function absoluteMediaUrls(string $html): string
    {
        return str_replace(
            '="/' . static::MEDIA_PATH . '/',
            '="' . url(static::MEDIA_PATH) . '/',
            $html
        );
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
            "text" => $button["linktext"] ?? "",
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
