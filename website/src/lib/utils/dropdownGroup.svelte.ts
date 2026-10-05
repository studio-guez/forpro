/**
 * At most one dropdown is open at a time, across every dropdown on the page:
 * opening one closes the others. Each instance takes the group as it opens and
 * closes on its own as soon as another one takes it. That, rather than a
 * pointer down outside, is what closes a dropdown when another one is clicked:
 * closing first would shift the layout under the pointer before the click
 * lands, and the click would miss the trigger it was aimed at.
 */
let current = $state<symbol | null>(null);

export const dropdownGroup = {
	take(token: symbol): void {
		current = token;
	},
	release(token: symbol): void {
		if (current === token) current = null;
	},
	holds(token: symbol): boolean {
		return current === token;
	}
};
