<?php

/**
 * Third-party embeds: the shared `fields/video` structure (an uploaded file
 * or a YouTube URL per row), the YouTube URL parsing behind it, and the
 * Google Maps iframe URL check.
 */
trait UtilsEmbeds
{
    /**
     * Returns the URL when it is a Google Maps embed (the `src` of the iframe
     * offered by "Share → Embed a map"), null otherwise. The frontend drops it
     * straight into an iframe, so anything not served by Google Maps is
     * refused rather than framed.
     */
    static function getGoogleMapsEmbedUrl(?string $url): ?string
    {
        if (!$url) return null;

        $parts = parse_url($url);
        if (($parts['scheme'] ?? '') !== 'https') return null;
        if (!in_array($parts['host'] ?? '', ['www.google.com', 'maps.google.com'], true)) return null;

        $path = $parts['path'] ?? '';
        parse_str($parts['query'] ?? '', $query);

        // `/maps/embed?pb=…` is the current share format; `/maps?…&output=embed`
        // the legacy one still found in older embed codes.
        $isEmbed = str_starts_with($path, '/maps/embed')
            || ($path === '/maps' && ($query['output'] ?? null) === 'embed');

        return $isEmbed ? $url : null;
    }

    /**
     * Extracts the 11-character YouTube video id from any common URL shape
     * (watch?v=, youtu.be/, /shorts/, /embed/, /v/). Returns null when the URL
     * is not a recognizable YouTube link.
     */
    static function parseYoutubeId(string $url): ?string
    {
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|shorts/|embed/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $url, $m) === 1) {
            return $m[1];
        }
        return null;
    }

    /**
     * Resolves a single YouTube URL to a JSON-ready embed, or null when it is
     * not a recognizable YouTube link. Shorts are flagged with `type => 'short'`
     * (vertical), everything else is `type => 'video'` (16:9). `embedUrl` is
     * the privacy-friendly nocookie URL.
     */
    static function getYoutubeEmbed(string $url): ?array
    {
        $id = self::parseYoutubeId($url);
        if (!$id) return null;

        return [
            'id'       => $id,
            'type'     => stripos($url, '/shorts/') !== false ? 'short' : 'video',
            'url'      => $url,
            'embedUrl' => 'https://www.youtube-nocookie.com/embed/' . $id,
        ];
    }

    /**
     * Resolves a `fields/video` structure to a JSON-ready list. Each entry is
     * either `{source: 'upload', file: <media>}` or
     * `{source: 'youtube', embed: <embed>}`; rows without a video file or
     * without a recognizable YouTube link are dropped.
     */
    static function getVideos(\Kirby\Content\Field $field): array
    {
        try {
            $rows = $field->toStructure();
        } catch (\Kirby\Exception\InvalidArgumentException) {
            // Content still in the pre-`fields/video` shape (a bare files list):
            // degrade to "no video" instead of a 500 until
            // `utils/migrate-event-project-body.php` has been run.
            return [];
        }

        $videos = [];
        foreach ($rows as $row) {
            $video = self::getVideoRowData($row);
            if ($video) $videos[] = $video;
        }
        return $videos;
    }

    /**
     * Single-row variant of getVideos() for fields capped at `max: 1`
     * (the `module-video` block).
     */
    static function getVideo(\Kirby\Content\Field $field): ?array
    {
        return self::getVideos($field)[0] ?? null;
    }

    private static function getVideoRowData(\Kirby\Cms\StructureObject $row): ?array
    {
        // Row fields are read through get(): `file`/`url` would otherwise be easy
        // to confuse with model methods of the same name.
        $content = $row->content();

        if ($content->get('source')->value() === 'youtube') {
            $embed = self::getYoutubeEmbed((string)$content->get('url')->value());
            return $embed ? ['source' => 'youtube', 'embed' => $embed] : null;
        }

        $file = $content->get('file')->toFile();
        if (!$file || $file->type() !== 'video') return null;

        return ['source' => 'upload', 'file' => self::getJsonEncodeMediaData($file)];
    }
}
