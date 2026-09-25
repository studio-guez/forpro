// Injected on demand: a `<svelte:head>` script never re-runs on client-side navigation.
// Matomo tracks cookieless (`requireCookieConsent`) until `setCookieConsent(true)`.

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
let lastConsent: boolean | null = null;

const queue = (): unknown[][] => (window._paq = window._paq ?? []);

/**
 * Lets Matomo set its cookies, or withdraws that and deletes the ones already set. Safe to call
 * before the first page view: commands queued before `matomo.js` loads run in order.
 */
export function setCookieConsent(granted: boolean): void {
	if (typeof window === 'undefined' || granted === lastConsent) return;
	lastConsent = granted;
	queue().push([granted ? 'rememberCookieConsentGiven' : 'forgetCookieConsentGiven']);
}

/**
 * Tracks one page view, injecting `matomo.js` on the first call. Safe to call on every
 * navigation: later calls only push a new page view onto the existing tracker.
 */
export function trackPageView(url: string): void {
	if (typeof window === 'undefined' || url === lastTrackedUrl) return;

	const previousUrl = lastTrackedUrl;
	lastTrackedUrl = url;

	const paq = queue();

	if (injected === false) {
		injected = true;
		paq.push(['requireCookieConsent']);
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
