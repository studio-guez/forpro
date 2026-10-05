/**
 * Listens for the two usual ways out of an open popover — a pointer down
 * outside `root`, or Escape — and reports which one it was: `byKeyboard` is
 * `true` for Escape, so the caller can send focus back to the trigger, and
 * `false` for a pointer, which has its own target.
 *
 * A pointer down inside an element matching `ignoreWithin` is not a way out:
 * the popovers of a group hand over to each other on click instead (see
 * `dropdownGroup`), which must not be pre-empted by a dismissal.
 *
 * Returns the cleanup, so it slots into an `$effect` run while the popover is open.
 */
export const listenForDismiss = (
	root: HTMLElement,
	onDismiss: (byKeyboard: boolean) => void,
	ignoreWithin?: string
): (() => void) => {
	const handlePointerDown = (event: PointerEvent) => {
		const target = event.target as Element;
		if (root.contains(target)) return;
		if (ignoreWithin && target.closest(ignoreWithin)) return;
		onDismiss(false);
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
