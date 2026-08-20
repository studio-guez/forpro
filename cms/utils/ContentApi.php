<?php

require_once __DIR__ . '/Utils.php';

/**
 * Shared API builder for the content types sharing the same base blueprint
 * (`pages/base-content.yml`): `event` and `project`.
 */
class ContentApi
{
    /**
     * Data shared by every page extending `pages/base-content`.
     */
    static function base(\Kirby\Cms\Page $page): array
    {
        return [
            'id'            => $page->id(),
            'uuid'          => $page->content()->uuid()->value(),
            'slug'          => $page->slug(),
            'uri'           => $page->uri(),
            'url'           => $page->url(),
            'template'      => $page->intendedTemplate()->name(),
            'title'         => $page->title()->value(),
            'subtitle'      => $page->subtitle()->value(),
            'shortDesc'     => $page->short_desc()->value(),
            'cover'         => self::cover($page),
            'medias'        => self::medias($page),
            'embedVideos'   => self::embedVideos($page),
            'blocks'        => self::blocks($page),
            'externalLinks' => self::externalLinks($page),
        ];
    }

    /**
     * Data of an `event` page.
     */
    static function event(\Kirby\Cms\Page $page): array
    {
        return array_merge(self::base($page), [
            'domains'     => $page->domains()->split(','),
            'eventThemes' => $page->event_themes()->split(','),
            'dateStart'   => self::dateTime($page->dateStart()),
            'dateEnd'     => self::dateTime($page->dateEnd()),
        ]);
    }

    /**
     * Data of a `project` page.
     */
    static function project(\Kirby\Cms\Page $page): array
    {
        return array_merge(self::base($page), [
            'projectThemes'     => $page->project_themes()->split(','),
            'projectTypes'      => $page->project_types()->split(','),
            'collectiveName'    => $page->collectiveName()->value(),
            'collectiveMembers' => $page->collectiveMembers()->toStructure()->map(function ($member) {
                return [
                    'name' => $member->name()->value(),
                ];
            })->values(),
        ]);
    }

    static function cover(\Kirby\Cms\Page $page): array|null
    {
        $cover = $page->cover()->toFiles()->first();

        return $cover ? Utils::getJsonEncodeImageData($cover) : null;
    }

    static function medias(\Kirby\Cms\Page $page): array
    {
        return array_values(Utils::getImageArrayDataInPage($page->medias()->toFiles()) ?? []);
    }

    static function blocks(\Kirby\Cms\Page $page): array
    {
        return $page->blocks()->toStructure()->map(function ($block) {
            return [
                'title'       => $block->title()->value(),
                'description' => $block->description()->value(),
            ];
        })->values();
    }

    static function externalLinks(\Kirby\Cms\Page $page): array
    {
        return $page->external_links()->toStructure()->map(function ($link) {
            return [
                'title' => $link->title()->value(),
                'link'  => $link->link()->value(),
            ];
        })->values();
    }

    /**
     * Youtube videos and shorts, ready to be embedded.
     */
    static function embedVideos(\Kirby\Cms\Page $page): array
    {
        $videos = $page->embed_videos()->toStructure()->map(function ($video) {
            $youtube = self::parseYoutubeUrl($video->url()->value());

            if ($youtube === null) return null;

            return array_merge($youtube, [
                'title' => $video->title()->isNotEmpty() ? $video->title()->value() : null,
                'url'   => $video->url()->value(),
            ]);
        })->values();

        return array_values(array_filter($videos));
    }

    /**
     * Extracts the video id from a Youtube url (video, short or embed url).
     * Returns null for any url which is not a supported Youtube url.
     */
    static function parseYoutubeUrl(string|null $url): array|null
    {
        if (empty($url)) return null;

        $patterns = [
            'video' => '!^https?://(?:www\.|m\.)?youtube\.com/watch\?(?:.*&)?v=([\w-]{11})!',
            'short' => '!^https?://(?:www\.|m\.)?youtube\.com/shorts/([\w-]{11})!',
            'embed' => '!^https?://(?:www\.|m\.)?youtube\.com/embed/([\w-]{11})!',
            'share' => '!^https?://youtu\.be/([\w-]{11})!',
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $url, $matches) !== 1) continue;

            $id = $matches[1];

            return [
                'id'        => $id,
                'isShort'   => $type === 'short',
                'embedUrl'  => 'https://www.youtube-nocookie.com/embed/' . $id,
                'thumbnail' => 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg',
            ];
        }

        return null;
    }

    /**
     * A date field with time enabled, exposed as an ISO string and its parts.
     */
    static function dateTime(\Kirby\Content\Field $field): array|null
    {
        if ($field->isEmpty()) return null;

        $timestamp = $field->toDate();

        if ($timestamp === null) return null;

        return [
            'iso'  => date('c', $timestamp),
            'date' => date('Y-m-d', $timestamp),
            'time' => date('H:i', $timestamp),
        ];
    }

    /**
     * Seo values with a fallback on the site values.
     */
    static function seo(\Kirby\Cms\Page $page, \Kirby\Cms\Site $site): array
    {
        $keys = [
            'metaTemplate', 'metaDescription', 'metaAuthor', 'metaImage', 'metaPhoneNumber',
            'ogTemplate', 'ogDescription', 'ogImage', 'ogSiteName',
            'twitterTemplate', 'twitterDescription', 'twitterImage', 'twitterCardType',
            'twitterSite', 'twitterCreator',
        ];

        $seo = [];

        foreach ($keys as $key) {
            if ($page->$key()->isNotEmpty()) {
                $seo[$key] = $page->$key()->value();
            } elseif ($site->$key()->isNotEmpty()) {
                $seo[$key] = $site->$key()->value();
            } else {
                $seo[$key] = "";
            }
        }

        return $seo;
    }
}
