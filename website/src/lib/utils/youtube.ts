const YOUTUBE_PATTERNS: { pattern: RegExp; isShort: boolean }[] = [
	{ pattern: /^https?:\/\/(?:www\.|m\.)?youtube\.com\/watch\?(?:.*&)?v=([\w-]{11})/, isShort: false },
	{ pattern: /^https?:\/\/(?:www\.|m\.)?youtube\.com\/shorts\/([\w-]{11})/, isShort: true },
	{ pattern: /^https?:\/\/(?:www\.|m\.)?youtube\.com\/embed\/([\w-]{11})/, isShort: false },
	{ pattern: /^https?:\/\/youtu\.be\/([\w-]{11})/, isShort: false }
];

export interface ParsedYoutubeUrl {
	id: string;
	isShort: boolean;
	embedUrl: string;
	thumbnail: string;
}

/**
 * Parses a Youtube url (video, short, share or embed url).
 * Returns null when the url is not a supported Youtube url.
 */
export function parseYoutubeUrl(url: string | null | undefined): ParsedYoutubeUrl | null {
	if (!url) return null;

	for (const { pattern, isShort } of YOUTUBE_PATTERNS) {
		const match = url.match(pattern);

		if (!match) continue;

		const id = match[1];

		return {
			id,
			isShort,
			embedUrl: `https://www.youtube-nocookie.com/embed/${id}`,
			thumbnail: `https://i.ytimg.com/vi/${id}/hqdefault.jpg`
		};
	}

	return null;
}
