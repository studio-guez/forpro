<script lang="ts">
	import { slide } from 'svelte/transition';
	import { prefersReducedMotion } from 'svelte/motion';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import { listenForDismiss } from '$lib/utils/dismiss';

	interface Option {
		readonly value: string;
		readonly label: string;
	}

	interface Props {
		value: string;
		options: Option[];
		label: string;
		/** Name of the selection-free state, announced by the clearing action. */
		allLabel?: string;
		/**
		 * Whether the panel offers a way back to the empty value. Turn it off when
		 * the empty value is not a selection-free state but an option of its own
		 * (a default order, say) — there is nothing left to clear then.
		 */
		clearable?: boolean;
		color?: string;
		class?: string;
	}

	let {
		value = $bindable(''),
		options,
		label,
		allLabel = 'Tous',
		clearable = true,
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();

	const uid = $props.id();
	const panelId = `select-${uid}`;

	let open = $state(false);
	let rootEl = $state<HTMLDivElement>();
	let triggerEl = $state<HTMLButtonElement>();

	// The trigger reads as the current selection; with none it reads as the field
	// itself. A list may offer the empty value as a real option (a default order,
	// say), and then it is a selection like any other.
	const selectedOption = $derived(options.find((option) => option.value === value));
	const selectedLabel = $derived(selectedOption?.label ?? label);

	const panelSlide = $derived({ duration: prefersReducedMotion.current ? 0 : 250 });

	// Focus goes back to the trigger whenever the panel is closed from the inside
	// (Escape, picking an option), otherwise it would be left on a gone element.
	const close = (focusTrigger: boolean): void => {
		open = false;
		if (focusTrigger) triggerEl?.focus();
	};

	// Clicking outside is the pointer way out; Escape is the keyboard one.
	$effect(() => {
		if (!open || !rootEl) return;
		return listenForDismiss(rootEl, close);
	});
</script>

<div
	bind:this={rootEl}
	style:--select-color={color}
	style:--select-tint="color-mix(in oklab, {color} 12%, transparent)"
	class="text-(--select-color) -mx-3 max-w-full {className}"
>
	<!-- The panel overlays the page rather than pushing it down: the trigger is the
	     top of the card and stays in flow, the panel is its bottom, positioned under
	     it. The panel paints over the trigger's shadow, so the two read as one card. -->
	<div class="relative w-fit min-w-64 max-w-lg">
		<!-- The panel is out of flow, so it cannot size the field: the label and
		     every option are laid out again here, invisibly, one line each, with the
		     rows' own padding and room for their icons. The field is then as wide as
		     the widest of them, whatever is selected, and the panel never wraps or
		     clips one. The max width is a safeguard only; the rows truncate once it
		     is reached. -->
		<div class="h-0 overflow-hidden invisible whitespace-nowrap" aria-hidden="true">
			<div class="text-label flex gap-3 px-3">
				<span>{label}</span>
				<span class="shrink-0 w-5.25"></span>
			</div>
			{#each options as option (option.value)}
				<div class="text-label flex gap-3 px-3">
					<span>{option.label}</span>
					<span class="shrink-0 w-4.5"></span>
				</div>
			{/each}
		</div>

		<button
			bind:this={triggerEl}
			type="button"
			class="text-label w-full text-left px-3 py-2 rounded-t-xl transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current {open
				? 'bg-white shadow-lg'
				: 'rounded-b-xl hover:bg-(--select-tint)'}"
			aria-expanded={open}
			aria-controls={open ? panelId : undefined}
			aria-label={selectedOption ? `${label} : ${selectedLabel}` : label}
			onclick={() => (open = !open)}
		>
			<div class="border-b-2 border-current flex items-center justify-between gap-3 pb-1">
				<div class="truncate">{selectedLabel}</div>
				<IconChevron
					class="shrink-0 w-5.25 h-5.25 transition-transform {open ? 'rotate-180' : ''}"
				/>
			</div>
		</button>

		{#if open}
			<div
				id={panelId}
				class="absolute inset-x-0 top-full z-20 pb-2 rounded-b-xl bg-white shadow-lg"
				transition:slide={panelSlide}
			>
				<!-- Arrow keys move *and* select inside a radio group, so the panel only
				     closes on a real click (`detail` is 0 for a keyboard-driven one) or
				     on an explicit Enter. -->
				<fieldset class="pt-2">
					<legend class="sr-only">{label}</legend>
					{#each options as option (option.value)}
						<label
							class="text-label flex items-center justify-between gap-3 px-3 py-1 cursor-pointer transition-colors hover:bg-(--select-tint)"
						>
							<span class="truncate">{option.label}</span>
							<input
								type="radio"
								name={panelId}
								value={option.value}
								bind:group={value}
								onclick={(event) => {
									if (event.detail > 0) close(true);
								}}
								onkeydown={(event) => {
									if (event.key !== 'Enter') return;
									event.preventDefault();
									close(true);
								}}
								class="sr-only peer"
							/>
							<!-- Clipping the fill to the content box leaves the ring and its gap: a radio dot. -->
							<span
								class="shrink-0 w-4.5 h-4.5 rounded-full border-2 border-current p-0.75 bg-clip-content peer-checked:bg-current peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-current"
							></span>
						</label>
					{/each}
				</fieldset>

				{#if clearable}
					<button
						type="button"
						class="text-label w-full text-left px-3 py-1 transition-colors enabled:hover:bg-(--select-tint) disabled:opacity-35 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
						disabled={value === ''}
						aria-label="Effacer : {allLabel}"
						onclick={() => {
							value = '';
							close(true);
						}}
					>
						Effacer
					</button>
				{/if}
			</div>
		{/if}
	</div>
</div>
