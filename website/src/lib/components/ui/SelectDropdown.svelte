<script lang="ts">
	import { slide } from 'svelte/transition';
	import { prefersReducedMotion } from 'svelte/motion';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

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
		color?: string;
		class?: string;
	}

	let {
		value = $bindable(''),
		options,
		label,
		allLabel = 'Tous',
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();

	const uid = $props.id();
	const panelId = `select-${uid}`;

	let open = $state(false);
	let rootEl = $state<HTMLDivElement>();
	let triggerEl = $state<HTMLButtonElement>();

	// The trigger reads as the current selection; with none it reads as the field itself.
	const selectedLabel = $derived(options.find((option) => option.value === value)?.label ?? label);

	const panelSlide = $derived({ duration: prefersReducedMotion.current ? 0 : 250 });

	// Focus goes back to the trigger whenever the panel is closed from the inside
	// (Escape, picking an option), otherwise it would be left on a gone element.
	const close = (focusTrigger: boolean): void => {
		open = false;
		if (focusTrigger) triggerEl?.focus();
	};

	// Clicking outside is the pointer way out; Escape is the keyboard one.
	$effect(() => {
		if (!open) return;

		const handlePointerDown = (event: PointerEvent) => {
			if (rootEl && !rootEl.contains(event.target as Node)) close(false);
		};
		const handleKeydown = (event: KeyboardEvent) => {
			if (event.key === 'Escape') close(true);
		};

		document.addEventListener('pointerdown', handlePointerDown);
		document.addEventListener('keydown', handleKeydown);
		return () => {
			document.removeEventListener('pointerdown', handlePointerDown);
			document.removeEventListener('keydown', handleKeydown);
		};
	});
</script>

<div
	bind:this={rootEl}
	style:--select-color={color}
	style:--select-tint="color-mix(in oklab, {color} 12%, transparent)"
	class="text-(--select-color) -mx-3 {className}"
>
	<!-- Open, the trigger becomes the head of the panel, so both share its rounding. -->
	<div
		class="w-64 max-w-full rounded-xl overflow-hidden transition-shadow {open
			? 'bg-white shadow-lg'
			: ''}"
	>
		<button
			bind:this={triggerEl}
			type="button"
			class="text-label w-full text-left px-3 py-2 rounded-t-xl transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current {open
				? ''
				: 'rounded-b-xl hover:bg-(--select-tint)'}"
			aria-expanded={open}
			aria-controls={panelId}
			aria-label={value === '' ? label : `${label} : ${selectedLabel}`}
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
			<div id={panelId} class="pb-2" transition:slide={panelSlide}>
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
			</div>
		{/if}
	</div>
</div>
