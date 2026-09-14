<script lang="ts">
	import type { Snippet } from 'svelte';
	import { MediaQuery } from 'svelte/reactivity';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import { CTA_BASE, ctaColorClasses, ctaSizeClasses } from '$lib/utils/ctaStyles';
	import { lockPageScroll } from '$lib/utils/smoothScroll';

	interface Props {
		/** Any CSS colour: drives the button and the modal on narrow screens. */
		color?: string;
		/** What the narrow-screen button and modal are called. */
		label?: string;
		class?: string;
		/** The filters, laid out at the start of the row; under the search in the modal. */
		children: Snippet;
		/** The control at the end of the row: the search, or a sort where there is none. First in the modal. */
		end?: Snippet;
	}

	let {
		color = 'var(--color-blue)',
		label = 'Filtrer et trier',
		class: className = '',
		children,
		end
	}: Props = $props();

	// Below this width the row gives way to a button and a modal. The same
	// breakpoint switches the dropdown panels from overlays to in-flow boxes and
	// the search from a fixed field to a full-width one, so the modal stacks
	// them cleanly.
	const isWide = new MediaQuery('(width >= 48rem)');

	let open = $state(false);
	let dialog = $state<HTMLDialogElement>();

	const close = (): void => {
		open = false;
	};

	$effect(() => {
		if (!open) return;
		return lockPageScroll();
	});

	$effect(() => {
		if (!dialog) return;
		if (open && !dialog.open) dialog.showModal();
		else if (!open && dialog.open) dialog.close();
	});

	// Growing past the breakpoint with the modal open would leave it over a row
	// that shows the same controls: the row takes over.
	$effect(() => {
		if (isWide.current) close();
	});
</script>

<!-- The row a list is browsed with: filters at the start, search at the end. It
     leaves no room below: what follows brings its own top padding, the same on
     every list. On narrow screens it is a single button opening a modal that
     stacks the same controls, search first. -->
<div class={['hidden md:flex flex-wrap items-center justify-between gap-x-6 gap-y-4', className]}>
	<div class="flex flex-wrap items-center gap-x-6 gap-y-3">
		{@render children()}
	</div>

	{@render end?.()}
</div>

<div class={['md:hidden', className]}>
	<button
		type="button"
		style:--color-cta={color}
		class="w-full justify-center {CTA_BASE} {ctaColorClasses(false)} {ctaSizeClasses.md}"
		aria-haspopup="dialog"
		onclick={() => (open = true)}
	>
		<span class="text-trim">{label}</span>
	</button>
</div>

<!-- The controls are only mounted while the modal is open, so they exist once
     at a time: the hidden row's copies from the breakpoint up, these below it. -->
<dialog
	bind:this={dialog}
	onclose={close}
	onclick={(event) => {
		if (event.target === dialog) close();
	}}
	aria-label={label}
	style:color
	class="md:hidden fixed inset-x-0 bottom-0 top-auto m-0 w-full max-w-none max-h-[calc(100dvh-2rem)] hidden open:flex flex-col overflow-hidden rounded-t-3xl bg-white p-0 shadow-2xl backdrop:bg-black/40"
>
	{#if open}
		<div
			class="flex items-center justify-between gap-4 px-5 py-4 border-b-2 border-grey-light shrink-0"
		>
			<h2 class="text-body-2 font-bold">{label}</h2>
			<button
				type="button"
				onclick={close}
				class="shrink-0 p-2 rounded-full hover:bg-(--color-cta) hover:text-white transition-colors"
				style:--color-cta={color}
				aria-label="Fermer"
			>
				<IconClose class="shrink-0 w-6.25 h-6.25" />
			</button>
		</div>

		<!-- The dropdowns pull themselves out by their own padding; the wider gutter
		     puts their text back in line with the title above. -->
		<div class="flex flex-col items-stretch gap-6 px-8 py-6 overflow-y-auto">
			{@render end?.()}
			{@render children()}
		</div>

		<div class="px-5 py-4 border-t-2 border-grey-light shrink-0">
			<button
				type="button"
				style:--color-cta={color}
				class="{CTA_BASE} {ctaColorClasses(false)} {ctaSizeClasses.md} w-full justify-center"
				onclick={close}
			>
				<span class="text-trim">Voir les résultats</span>
			</button>
		</div>
	{/if}
</dialog>
