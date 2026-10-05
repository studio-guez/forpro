import { browser } from '$app/environment';

const STORAGE_KEY = 'forpro.cookieConsent';
/**
 * Bump when the scope of the consent changes or a new vendor is added: a stored record from an
 * older version is discarded and the visitor is asked again, which is what consent law requires.
 */
const VERSION = 2;

interface StoredConsent {
	readonly version: number;
	readonly accepted: boolean;
	readonly date: string;
}

/** Null until the visitor has answered. */
let accepted = $state<boolean | null>(null);
/** Stays false until the first client-side read, so SSR renders no banner and hydration matches. */
let loaded = $state(false);
/** Set by a "manage my cookies" control: the banner shows again although a choice is stored. */
let reopened = $state(false);

function read(): boolean | null {
	try {
		const raw = localStorage.getItem(STORAGE_KEY);
		if (!raw) return null;
		const stored = JSON.parse(raw) as Partial<StoredConsent>;
		if (stored.version !== VERSION) return null;
		return stored.accepted === true;
	} catch {
		// Private browsing throws on localStorage and a hand-edited value throws on parse: ask again.
		return null;
	}
}

function write(value: boolean): void {
	try {
		const stored: StoredConsent = {
			version: VERSION,
			accepted: value,
			date: new Date().toISOString()
		};
		localStorage.setItem(STORAGE_KEY, JSON.stringify(stored));
	} catch {
		// Storage unavailable: the choice still holds for this page view, it just cannot be remembered.
	}
}

function save(value: boolean): void {
	accepted = value;
	reopened = false;
	write(value);
}

/**
 * Consent to optional cookies. The only vendor behind it is Matomo, which tracks cookieless
 * until this is accepted.
 */
export const cookieConsent = {
	/** True once the stored record has been read; nothing should render before that. */
	get loaded(): boolean {
		return loaded;
	},
	/** True once the visitor has answered — the banner shows while this is false. */
	get decided(): boolean {
		return accepted !== null;
	},
	/** True while the visitor has asked to revisit a stored choice; cleared by the next answer. */
	get reopened(): boolean {
		return reopened;
	},
	/** False until the visitor explicitly accepts. */
	get accepted(): boolean {
		return accepted === true;
	},
	/** Reads the stored record. Call once, on mount. */
	load(): void {
		if (!browser || loaded) return;
		accepted = read();
		loaded = true;
	},
	accept(): void {
		save(true);
	},
	refuse(): void {
		save(false);
	},
	/** Brings the banner back; the stored choice stays until the visitor answers again. */
	reopen(): void {
		reopened = true;
	}
};
