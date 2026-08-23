<?php

/**
 * YouTube embed parsing for the `embedVideos` structure.
 */
trait UtilsEmbeds
{
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
     * Resolves an `embedVideos` structure (each item exposing a `url` field)
     * to a JSON-ready list of YouTube embeds. Only YouTube links are kept;
     * Shorts are flagged with `type => 'short'` (vertical), everything else is
     * `type => 'video'` (16:9). `embedUrl` is the privacy-friendly nocookie URL.
     */
    static function getYoutubeEmbeds(\Kirby\Content\Field $field): array
    {
        $embeds = [];
        foreach ($field->toStructure() as $item) {
            $url = $item->url()->value();
            $id  = self::parseYoutubeId((string)$url);
            if (!$id) continue;
            $embeds[] = [
                'id'       => $id,
                'type'     => stripos((string)$url, '/shorts/') !== false ? 'short' : 'video',
                'url'      => $url,
                'embedUrl' => 'https://www.youtube-nocookie.com/embed/' . $id,
            ];
        }
        return $embeds;
    }
}
