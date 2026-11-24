import type { ContrastPreference } from '$lib/utils/contrast.svelte';

declare global {
	namespace App {
		// interface Error {}
		interface Locals {
			contrast: ContrastPreference;
		}
		interface PageData {
			/**
			 * Set by the root layout. Server-side rendering reads it here: the contrast store is
			 * module state, shared by every request on the server.
			 */
			contrast?: ContrastPreference;
		}
		// interface PageState {}
		// interface Platform {}
	}
}

export {};
