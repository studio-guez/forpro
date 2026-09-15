import { browser } from '$app/environment';

/**
 * The three categories the banner offers. `necessary` is not a choice — it is stored
 * only so the persisted record is self-describing.
 */
export interface CookieConsent {
	readonly necessary: true;
	readonly performance: boolean;
	readonly marketing: boolean;
}

/** What the banner writes back; `necessary` is not editable. */
export type CookieChoice = Omit<CookieConsent, 'necessary'>;

const STORAGE_KEY = 'forpro.cookieConsent';
/**
 * Bump when the categories change or a new vendor is added: a stored record from an older
 * version is discarded and the visitor is asked again, which is what consent law requires.
 */
const VERSION = 1;

interface StoredConsent extends CookieConsent {
	readonly version: number;
	readonly date: string;
}

let consent = $state<CookieConsent | null>(null);
/** Stays false until the first client-side read, so SSR renders no banner and hydration matches. */
let loaded = $state(false);
/** Set by a "manage my cookies" control: the banner shows again although a choice is stored. */
let reopened = $state(false);

function read(): CookieConsent | null {
	try {
		const raw = localStorage.getItem(STORAGE_KEY);
		if (!raw) return null;
		const stored = JSON.parse(raw) as Partial<StoredConsent>;
		if (stored.version !== VERSION) return null;
		return {
			necessary: true,
			performance: stored.performance === true,
			marketing: stored.marketing === true
		};
	} catch {
		// Private browsing throws on localStorage and a hand-edited value throws on parse: ask again.
		return null;
	}
}

function write(value: CookieConsent): void {
	try {
		const stored: StoredConsent = { ...value, version: VERSION, date: new Date().toISOString() };
		localStorage.setItem(STORAGE_KEY, JSON.stringify(stored));
	} catch {
		// Storage unavailable: the choice still holds for this page view, it just cannot be remembered.
	}
}

export const cookieConsent = {
	/** True once the stored record has been read; nothing should render before that. */
	get loaded(): boolean {
		return loaded;
	},
	/** True once the visitor has answered — the banner shows while this is false. */
	get decided(): boolean {
		return consent !== null;
	},
	/** True while the visitor has asked to revisit a stored choice; cleared by the next save. */
	get reopened(): boolean {
		return reopened;
	},
	get performance(): boolean {
		return consent?.performance ?? false;
	},
	get marketing(): boolean {
		return consent?.marketing ?? false;
	},
	/** Reads the stored record. Call once, on mount. */
	load(): void {
		if (!browser || loaded) return;
		consent = read();
		loaded = true;
	},
	save(choice: CookieChoice): void {
		consent = { necessary: true, ...choice };
		reopened = false;
		write(consent);
	},
	acceptAll(): void {
		cookieConsent.save({ performance: true, marketing: true });
	},
	/** Brings the banner back on its preferences view; the stored choice stays until it is saved again. */
	reopen(): void {
		reopened = true;
	}
};
