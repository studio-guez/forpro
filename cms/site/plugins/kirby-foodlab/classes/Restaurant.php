<?php

namespace Eclypsys;

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
        static::write(
            static::changesFile(),
            static::toStoredValues($values, static::changes() ?? static::get())
        );
    }

    /**
     * Writes the draft to the stored content and drops it
     */
    public static function publish(array $values): array
    {
        $data = static::toStoredValues($values, static::get());

        static::write(static::file(), $data);

        F::remove(static::changesFile());

        return $data;
    }

    public static function discard(): void
    {
        F::remove(static::changesFile());
    }

    /**
     * Panel form values -> stored content. `Form` lowercases every field key,
     * so they are mapped back to the camelCase keys of fields/restaurant.php.
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

        $submitted = array_change_key_case($values, CASE_LOWER);
        $stored = array_intersect_key(
            static::form($values)->toStoredValues(),
            $submitted
        );

        return [...$data, ...static::restoreKeys($stored, static::keyMap())];
    }

    /**
     * Writes JSON under an exclusive lock
     */
    private static function write(string $file, array $data): void
    {
        $lock = fopen($file . ".lock", "c");

        if ($lock === false || flock($lock, LOCK_EX) === false) {
            throw new Exception(message: "Failed to acquire write lock.");
        }

        try {
            if (Json::write($file, $data) === false) {
                throw new Exception(message: "Failed to write " . basename($file) . ".");
            }
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /**
     * Lowercased field name -> original name (+ subfields), so the stored
     * content keeps the camelCase keys the rest of the plugin reads
     */
    private static function keyMap(array|null $fields = null): array
    {
        $map = [];

        foreach ($fields ?? static::fields() as $name => $field) {
            $map[strtolower($name)] = [
                "key" => $name,
                "type" => $field["type"] ?? "text",
                "fields" => isset($field["fields"]) === true
                    ? static::keyMap($field["fields"])
                    : null,
            ];
        }

        return $map;
    }

    private static function restoreKeys(array $values, array $map): array
    {
        $result = [];

        foreach ($values as $name => $value) {
            $entry = $map[strtolower($name)] ?? null;
            $fields = $entry["fields"] ?? null;

            if ($fields !== null && is_array($value) === true) {
                $value = array_is_list($value) === true
                    // structure: a list of rows
                    ? array_map(
                        fn($row) => is_array($row) === true
                            ? static::restoreKeys($row, $fields)
                            : $row,
                        $value
                    )
                    // object: a single row
                    : static::restoreKeys($value, $fields);
            }

            // Form::toStoredValues() targets Kirby's flat text files, where
            // everything is a string; JSON can hold the boolean as it is
            if (($entry["type"] ?? null) === "toggle") {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            $result[$entry["key"] ?? $name] = $value;
        }

        return $result;
    }

    /**
     * The form of the restaurant content, filled with the given values.
     *
     * The site is passed as the model so field types that expect one (the
     * link and textarea previews) keep working; nothing is ever written to it.
     */
    public static function form(array $values = []): Form
    {
        $form = new Form(fields: static::fields(), model: kirby()->site());

        return $form->fill(input: $values);
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

        $lock = fopen(static::file() . '.lock', 'c');

        if ($lock === false || flock($lock, LOCK_EX) === false) {
            throw new Exception(message: "Failed to acquire write lock.");
        }

        try {
            $data = static::get();
            $path = "/" . static::MEDIA_PATH . "/" . $filename;

            foreach (["btnLab", "btnFooter1"] as $key) {
                $button = $data[$key] ?? [];
                $button["link"] = $path;
                $button["target"] = true;
                $data[$key] = $button;
            }

            $data["menuPdf"] = $filename;

            if (Json::write(static::file(), $data) === false) {
                throw new Exception(message: "Failed to update restaurant data.");
            }

            // an editor may have unsaved changes open; the published menu must
            // not be undone the next time they hit save
            if ($changes = static::changes()) {
                foreach (["btnLab", "btnFooter1"] as $key) {
                    $changes[$key] = $data[$key];
                }

                $changes["menuPdf"] = $filename;

                Json::write(static::changesFile(), $changes);
            }
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
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
     * Renders a textarea value with KirbyText, like the site fields did.
     *
     * Media inserted through the file button of the toolbar is stored as a
     * root-relative path so the content stays portable between environments;
     * the frontend runs on another host, so it is made absolute here.
     */
    private static function text(array $data, string $key): string
    {
        $value = static::value($data, $key);

        if ($value === "") {
            return "";
        }

        return str_replace(
            '="/' . static::MEDIA_PATH . '/',
            '="' . url(static::MEDIA_PATH) . '/',
            (string) kirbytext($value)
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
