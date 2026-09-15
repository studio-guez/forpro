// Injected on demand: Matomo must not load before the performance category is accepted, and a `<svelte:head>` script never re-runs on client-side navigation.

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

	// Matomo keeps the first page view's URL until told otherwise and needs the previous one as the referrer.
	if (previousUrl) {
		paq.push(['setReferrerUrl', previousUrl]);
	}
	paq.push(['setCustomUrl', url]);
	paq.push(['trackPageView']);
	paq.push(['enableLinkTracking']);
}
