import { browser } from '$app/environment';

/**
 * Read server-side and written onto `<html data-contrast>` (see `hooks.server.ts`), so the first
 * paint already has the right colours. Without the cookie the mode follows `prefers-contrast`.
 */
export const CONTRAST_COOKIE = 'forpro.contrast';

const MAX_AGE = 60 * 60 * 24 * 365;
const SYSTEM_QUERY = '(prefers-contrast: more)';

export type ContrastPreference = 'high' | 'normal' | 'auto';

export const parseContrastPreference = (value: string | null | undefined): ContrastPreference =>
	value === 'high' || value === 'normal' ? value : 'auto';

/** The `<source media>` that matches exactly when the `hc:` variant of `app.css` applies. */
export const highContrastMedia = (preference: ContrastPreference): string =>
	preference === 'high' ? 'all' : preference === 'normal' ? 'not all' : SYSTEM_QUERY;

// Read from the server-rendered attribute at load, so hydration already sees the server's value.
let preference = $state<ContrastPreference>(
	browser ? parseContrastPreference(document.documentElement.dataset.contrast) : 'auto'
);
let systemPrefersMore = $state(false);
let initialized = false;

export const contrast = {
	/** Whether the high-contrast styles currently apply, whichever way they were requested. */
	get active(): boolean {
		return preference === 'high' || (preference === 'auto' && systemPrefersMore);
	},
	/** The visitor's explicit choice, or `auto` to follow the system setting. */
	get preference(): ContrastPreference {
		return preference;
	},
	/** Tracks the system setting. Call once, on mount. */
	init(): (() => void) | undefined {
		if (!browser || initialized) return;
		initialized = true;
		const query = window.matchMedia(SYSTEM_QUERY);
		systemPrefersMore = query.matches;
		const onChange = (event: MediaQueryListEvent) => (systemPrefersMore = event.matches);
		query.addEventListener('change', onChange);
		return () => {
			query.removeEventListener('change', onChange);
			initialized = false;
		};
	},
	/** Stores the opposite of the current state as an explicit choice, overriding the system setting. */
	toggle(): void {
		preference = contrast.active ? 'normal' : 'high';
		document.documentElement.dataset.contrast = preference;
		document.cookie = `${CONTRAST_COOKIE}=${preference}; Path=/; Max-Age=${MAX_AGE}; SameSite=Lax`;
	}
};
