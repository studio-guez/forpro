import { variables } from '$lib/utils/constants';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { Global } from '$lib/interfaces/global';
import type { RequestHandler } from '@sveltejs/kit';

// PWA manifest built from the same global.json favicon data as the <head> icons.
// Manifests ignore prefers-color-scheme, so we use the light PNG masters.
export const GET: RequestHandler = async () => {
	const request = new Request(`${variables.CMS_BASE_URL}/global.json`, {
		headers: getHeaders()
	});

	const global = await fetchFromAPI<Global>(request, 'Failed to load global data');

	const icons = (global?.favicon?.light.png ?? [])
		.filter((icon) => icon.size === 192 || icon.size === 512)
		.map((icon) => ({
			src: icon.url,
			sizes: `${icon.size}x${icon.size}`,
			type: 'image/png'
		}));

	const manifest = {
		name: global?.header.siteTitle ?? '',
		short_name: global?.header.siteTitle ?? '',
		icons,
		theme_color: '#ffffff',
		background_color: '#ffffff',
		display: 'standalone'
	};

	return new Response(JSON.stringify(manifest), {
		headers: { 'Content-Type': 'application/manifest+json' }
	});
};
