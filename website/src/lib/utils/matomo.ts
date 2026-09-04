// Matomo is a performance cookie: it must not load before the visitor has accepted that
// category, so the tracker is injected from here on demand instead of from a `<script>`
// tag in `<svelte:head>` (which would also never re-run on a client-side navigation).

declare global {
	interface Window {
		_paq?: unknown[][];
	}
}

const MATOMO_URL = '//matomo.for-pro.ch/';
const SITE_ID = '1';

let injected = false;
/** Guards against double counting when an effect re-runs on the same URL. */
let lastTrackedUrl: string | null = null;

/**
 * Tracks one page view, injecting `matomo.js` on the first call. Safe to call on every
 * navigation: later calls only push a new page view onto the existing tracker.
 */
export function trackPageView(url: string): void {
	if (typeof window === 'undefined' || url === lastTrackedUrl) return;

	const previousUrl = lastTrackedUrl;
	lastTrackedUrl = url;

	const paq = (window._paq = window._paq ?? []);

	if (injected === false) {
		injected = true;
		paq.push(['setTrackerUrl', MATOMO_URL + 'matomo.php']);
		paq.push(['setSiteId', SITE_ID]);
		paq.push(['setCustomUrl', url]);
		paq.push(['trackPageView']);
		paq.push(['enableLinkTracking']);

		const script = document.createElement('script');
		script.async = true;
		script.src = MATOMO_URL + 'matomo.js';
		document.head.appendChild(script);
		return;
	}

	// A SPA navigation: Matomo keeps the URL of the first page view until told otherwise,
	// and needs the previous one as the referrer to keep the visit's path intact.
	if (previousUrl) {
		paq.push(['setReferrerUrl', previousUrl]);
	}
	paq.push(['setCustomUrl', url]);
	paq.push(['trackPageView']);
	paq.push(['enableLinkTracking']);
}
