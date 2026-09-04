import { createHash } from 'node:crypto';
import { json } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { NewsletterResponse, NewsletterStatus } from '$lib/interfaces/global';
import type { RequestHandler } from './$types';

/** Server-only half of the CMS `newsletter` settings — never forwarded to the browser. */
interface NewsletterProvider {
	actionUrl: string | null;
	challengeUrl: string | null;
	key: string | null;
	webformId: string | null;
	emailFieldName: string | null;
	honeypotFields: string[];
}

/** An ALTCHA proof-of-work challenge, as served by the provider's `challengeUrl`. */
interface AltchaChallenge {
	algorithm: string;
	challenge: string;
	maxNumber?: number;
	salt: string;
	signature: string;
}

/** The provider's submit response. `err` is `[]`/`{}` on success, and either
 *  `["altcha"]` for a rejected captcha or `{"<field>": "<message>"}` otherwise. */
interface SubmitResult {
	err?: Record<string, string> | string[];
	redir?: string;
}

/** What the provider returns instead, with a 4xx/5xx, when the request never got as far
 *  as being validated — its own rate limit, or an error on their side. */
interface SubmitError {
	result?: string;
	error?: { code?: string; description?: string };
}

// Deliberately loose: the provider re-validates, and a stricter pattern would reject
// valid addresses. This only exists to keep obvious junk off the provider.
const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
const MAX_EMAIL_LENGTH = 254;
const TIMEOUT_MS = 10_000;

// The provider's challenges are sized at 100k (~40ms). The ceiling is a guard against a
// hostile or mis-sized `maxNumber` pinning a server thread, not a real expectation.
const MAX_SOLVE_ITERATIONS = 2_000_000;

// Solving the proof-of-work here rather than in the browser means this endpoint, not the
// provider's captcha, is what stands between a bot and the mailing list.
const RATE_LIMIT = 3;
const RATE_WINDOW_MS = 60_000;
const recentSubmissions = new Map<string, number[]>();

const isRateLimited = (address: string): boolean => {
	const now = Date.now();
	const fresh = (recentSubmissions.get(address) ?? []).filter((at) => now - at < RATE_WINDOW_MS);

	// Drop addresses that aged out entirely, so the map cannot grow without bound.
	for (const [key, times] of recentSubmissions) {
		if (times.every((at) => now - at >= RATE_WINDOW_MS)) recentSubmissions.delete(key);
	}

	if (fresh.length >= RATE_LIMIT) {
		recentSubmissions.set(address, fresh);

		return true;
	}

	recentSubmissions.set(address, [...fresh, now]);

	return false;
};

/** Finds the `number` whose SHA-256 with the salt reproduces the challenge hash. */
const solveChallenge = (challenge: AltchaChallenge): number | null => {
	const ceiling = Math.min(challenge.maxNumber ?? MAX_SOLVE_ITERATIONS, MAX_SOLVE_ITERATIONS);

	for (let number = 0; number <= ceiling; number++) {
		const hash = createHash('sha256')
			.update(challenge.salt + number)
			.digest('hex');

		if (hash === challenge.challenge) return number;
	}

	return null;
};

const reply = (status: NewsletterStatus, httpStatus: number) =>
	json({ status } satisfies NewsletterResponse, { status: httpStatus });

/**
 * Proxies a footer subscription to the newsletter provider.
 *
 * The provider's own embed code is a cross-origin form whose submit is gated by an ALTCHA
 * proof-of-work captcha; a `fetch` to it from the browser is either CORS-blocked or opaque.
 * Doing the whole exchange here — fetch challenge, solve it, submit — keeps the visitor on
 * the page, keeps the provider's scripts off the site, and gives us the provider's own
 * machine-readable verdict to turn into a success or error message.
 */
export const POST: RequestHandler = async ({ request, getClientAddress }) => {
	if (isRateLimited(getClientAddress())) return reply('error', 429);

	const body = await request.json().catch(() => null);
	const email = typeof body?.email === 'string' ? body.email.trim() : '';

	if (email.length > MAX_EMAIL_LENGTH || EMAIL.test(email) === false) {
		return reply('invalidEmail', 400);
	}

	const globalRequest = new Request(`${CMS_SERVER_BASE_URL}/global.json`, {
		headers: getHeaders()
	});
	const global = await fetchFromAPI<{ newsletter: NewsletterProvider }>(
		globalRequest,
		'Failed to load the newsletter provider settings'
	);
	const provider = global?.newsletter;

	// Read from the CMS rather than from the request body: the browser must not get to
	// choose where the site submits to.
	if (
		!provider?.actionUrl ||
		!provider.challengeUrl ||
		!provider.key ||
		!provider.webformId ||
		!provider.emailFieldName
	) {
		console.error('Newsletter: the provider settings are incomplete in the Panel');

		return reply('error', 500);
	}

	for (const url of [provider.actionUrl, provider.challengeUrl]) {
		if (url.startsWith('https://') === false) {
			console.error(`Newsletter: refusing a non-https provider URL (${url})`);

			return reply('error', 500);
		}
	}

	try {
		const challengeResponse = await fetch(provider.challengeUrl, {
			headers: { Accept: 'application/json' },
			signal: AbortSignal.timeout(TIMEOUT_MS)
		});

		if (challengeResponse.ok === false) {
			console.error(
				`Newsletter: could not get a captcha challenge (${challengeResponse.status} ${challengeResponse.statusText})`
			);

			return reply('error', 502);
		}

		const challenge: AltchaChallenge = await challengeResponse.json();

		// The payload below is only valid for SHA-256; a different algorithm means the
		// provider changed the scheme and needs a code change, not a silent wrong answer.
		if (challenge.algorithm !== 'SHA-256') {
			console.error(`Newsletter: unsupported captcha algorithm (${challenge.algorithm})`);

			return reply('error', 502);
		}

		const startedAt = Date.now();
		const number = solveChallenge(challenge);

		if (number === null) {
			console.error('Newsletter: no solution found for the captcha challenge');

			return reply('error', 502);
		}

		// Exactly what the provider's widget puts in the form's hidden `altcha` input.
		const altcha = Buffer.from(
			JSON.stringify({
				algorithm: challenge.algorithm,
				challenge: challenge.challenge,
				number,
				salt: challenge.salt,
				signature: challenge.signature,
				took: Date.now() - startedAt
			})
		).toString('base64');

		const payload = new URLSearchParams({
			key: provider.key,
			webform_id: provider.webformId,
			[provider.emailFieldName]: email,
			altcha
		});

		// Decoys, submitted empty exactly as a real visitor's browser would.
		for (const name of provider.honeypotFields ?? []) payload.set(name, '');

		const submitResponse = await fetch(provider.actionUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
				Accept: 'application/json'
			},
			body: payload,
			signal: AbortSignal.timeout(TIMEOUT_MS)
		});

		if (submitResponse.ok === false) {
			// The provider describes these in the body; the status alone does not say
			// whether we were throttled, mis-configured, or hit a bug on their side.
			const details: SubmitError = await submitResponse.json().catch(() => ({}));
			const code = details.error?.code ?? 'no code';
			const description = details.error?.description ?? 'no description';

			console.error(
				`Newsletter: the provider rejected the subscription (${submitResponse.status} ${submitResponse.statusText}) — ${code}: ${description}`
			);

			return reply('error', submitResponse.status === 429 ? 429 : 502);
		}

		const result: SubmitResult = await submitResponse.json();
		const errors = Object.values(result.err ?? {});

		if (errors.length === 0) return reply('ok', 200);

		// A rejected captcha is our problem, not the visitor's — it must not be reported
		// as a bad address, and it is the first thing to look at if this starts failing.
		if (errors.includes('altcha')) {
			console.error('Newsletter: the provider rejected our captcha solution');

			return reply('error', 502);
		}

		// Anything else is a per-field complaint, and the form has one field.
		return reply('invalidEmail', 400);
	} catch (error) {
		console.error(`Newsletter: could not reach the provider: ${error}`);

		return reply('error', 502);
	}
};
