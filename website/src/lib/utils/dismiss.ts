/**
 * Listens for the two usual ways out of an open popover — a pointer down
 * outside `root`, or Escape — and reports which one it was: `byKeyboard` is
 * `true` for Escape, so the caller can send focus back to the trigger, and
 * `false` for a pointer, which has its own target.
 *
 * Returns the cleanup, so it slots into an `$effect` run while the popover is open.
 */
export const listenForDismiss = (
	root: HTMLElement,
	onDismiss: (byKeyboard: boolean) => void
): (() => void) => {
	const handlePointerDown = (event: PointerEvent) => {
		if (!root.contains(event.target as Node)) onDismiss(false);
	};
	const handleKeydown = (event: KeyboardEvent) => {
		if (event.key === 'Escape') onDismiss(true);
	};

	document.addEventListener('pointerdown', handlePointerDown);
	document.addEventListener('keydown', handleKeydown);
	return () => {
		document.removeEventListener('pointerdown', handlePointerDown);
		document.removeEventListener('keydown', handleKeydown);
	};
};
