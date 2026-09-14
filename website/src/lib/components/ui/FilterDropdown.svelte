<script lang="ts">
	import { slide } from 'svelte/transition';
	import { prefersReducedMotion } from 'svelte/motion';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import type { TaxonomyFilterTerm, TaxonomyTerm } from '$lib/interfaces/taxonomy';
	import { listenForDismiss } from '$lib/utils/dismiss';
	import { termColor } from '$lib/utils/shared';
	import { dropdownGroup } from '$lib/utils/dropdownGroup.svelte';

	interface Props {
		terms: TaxonomyFilterTerm[];
		selected: string[];
		/** What the terms filter on, e.g. "Publics": the trigger reads as it. */
		label: string;
		clearLabel?: string;
		color?: string;
		class?: string;
	}

	let {
		terms,
		selected = $bindable([]),
		label,
		clearLabel = 'Réinitialiser les filtres',
		color = 'var(--color-blue)',
		class: className = ''
	}: Props = $props();

	const uid = $props.id();
	const panelId = `filter-${uid}`;

	let open = $state(false);
	let rootEl = $state<HTMLDivElement>();
	let triggerEl = $state<HTMLButtonElement>();

	// The trigger never lists what is selected, only how much: the panel is a
	// click away, and the count keeps the pill the same size whatever is picked.
	const count = $derived(selected.length);
	const isActive = $derived(count > 0);

	const panelSlide = $derived({ duration: prefersReducedMotion.current ? 0 : 250 });

	// Focus goes back to the trigger whenever the panel is closed from the inside
	// (Escape), otherwise it would be left on a gone element.
	const close = (focusTrigger: boolean): void => {
		open = false;
		if (focusTrigger) triggerEl?.focus();
	};

	// Clicking outside is the pointer way out; Escape is the keyboard one.
	// Clicking another dropdown is neither: it takes the group, which closes
	// this one only once its own click has landed.
	$effect(() => {
		if (!open || !rootEl) return;
		return listenForDismiss(rootEl, close, '[data-dropdown]');
	});

	const token = Symbol();
	$effect(() => {
		if (open) dropdownGroup.take(token);
		else dropdownGroup.release(token);
	});
	$effect(() => {
		if (open && !dropdownGroup.holds(token)) close(false);
	});

	// Sub-terms are always listed, but only a selected parent's count: it stands
	// for all of them, so they all read as selected until one of them narrows the
	// selection down.
	const isChildSelected = (parent: TaxonomyFilterTerm, child: TaxonomyTerm): boolean =>
		selected.includes(parent.slug) &&
		(selected.includes(child.slug) || !parent.children.some((c) => selected.includes(c.slug)));

	// Sub-terms read as selected while their parent is, so unticking one takes it
	// out: the parent narrows down to the sub-terms left. Taking the last one out
	// deselects the parent as well, and leaving them all in is stored as the
	// parent alone, its default state. Ticking a sub-term of an unselected parent
	// selects the parent, narrowed down to that sub-term.
	const toggleChild = (parent: TaxonomyFilterTerm, child: TaxonomyTerm): void => {
		if (!selected.includes(parent.slug)) {
			selected = [...selected, parent.slug, child.slug];
			return;
		}

		const kept = parent.children
			.filter((term) => isChildSelected(parent, term) !== (term.slug === child.slug))
			.map((term) => term.slug);

		const dropped = new Set(parent.children.map((term) => term.slug));
		const base = selected.filter((slug) => !dropped.has(slug));

		if (kept.length === 0) {
			selected = base.filter((slug) => slug !== parent.slug);
			return;
		}

		selected = kept.length === parent.children.length ? base : [...base, ...kept];
	};

	// Deselecting a parent takes its sub-terms' selection with it: they only
	// count while it is selected.
	const toggleParent = (term: TaxonomyFilterTerm): void => {
		if (!selected.includes(term.slug)) {
			selected = [...selected, term.slug];
			return;
		}

		const dropped = new Set([term.slug, ...term.children.map((child) => child.slug)]);
		selected = selected.filter((slug) => !dropped.has(slug));
	};

	const clear = (): void => {
		selected = [];
	};

	// A term without a colour of its own takes the dropdown's, not the site-wide
	// fallback: the panel then stays in the page colour rather than turning teal.
	const optionColor = (term: TaxonomyTerm): string => (term.color ? termColor(term) : color);
</script>

<!-- Each row is drawn in its term's own colour, as the term tags are: text, tick
     box and hover tint alike. -->
{#snippet option(term: TaxonomyTerm, isSelected: boolean, onchange: () => void, isChild: boolean)}
	<label
		style:color={optionColor(term)}
		style:--option-tint="color-mix(in oklab, {optionColor(term)} 12%, transparent)"
		class="text-label flex items-center justify-between gap-3 py-1 pr-3 cursor-pointer transition-colors hover:bg-(--option-tint) {isChild
			? 'pl-8'
			: 'pl-3'}"
	>
		<span class="truncate-x">{term.title}</span>
		<input type="checkbox" checked={isSelected} {onchange} class="sr-only peer" />
		<!-- Clipping the fill to the content box leaves the ring and its gap: a tick box. -->
		<span
			class="shrink-0 w-4.5 h-4.5 rounded-sm border-2 border-current p-0.75 bg-clip-content peer-checked:bg-current peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-current"
		></span>
	</label>
{/snippet}

{#if terms.length > 0}
	<div
		bind:this={rootEl}
		data-dropdown
		style:--select-color={color}
		style:--select-tint="color-mix(in oklab, {color} 12%, transparent)"
		class={['text-(--select-color) -mx-3 max-w-full', className]}
	>
		<!-- From the tablet breakpoint up the panel overlays the page rather than
		     pushing it down: the field row is the top of the card and stays in flow,
		     the panel is its bottom, positioned under it. The panel paints over the
		     row's shadow, so the two read as one card. Below it, in the toolbar's
		     modal, the panel takes up space instead and the field runs full width. -->
		<div
			class="relative w-full md:w-fit min-w-64 md:max-w-lg rounded-xl transition-shadow {open
				? 'max-md:shadow-lg'
				: ''}"
		>
			<!-- The panel is out of flow, so it cannot size the field: the label and
			     every term are laid out again here, invisibly, one line each, with the
			     rows' own padding and room for their icons. The field is then as wide as
			     the widest of them and the panel never wraps or clips one. The max width
			     is a safeguard only; the rows truncate once it is reached. -->
			<div class="h-0 overflow-hidden invisible whitespace-nowrap" aria-hidden="true">
				<div class="text-label flex gap-3 px-3">
					<span>{label}</span>
					<span class="shrink-0 flex gap-2">
						<span class="min-w-5 px-1.5"></span>
						<span class="w-5.25"></span>
					</span>
				</div>
				{#each terms as term (term.slug)}
					<div class="text-label flex gap-3 pl-3 pr-3">
						<span>{term.title}</span>
						<span class="shrink-0 w-4.5"></span>
					</div>
					{#each term.children as child (child.slug)}
						<div class="text-label flex gap-3 pl-8 pr-3">
							<span>{child.title}</span>
							<span class="shrink-0 w-4.5"></span>
						</div>
					{/each}
				{/each}
			</div>

			<!-- The field tints as soon as something is selected, so an active filter
			     reads from across the page; the count only comes with it. -->
			<button
				bind:this={triggerEl}
				type="button"
				class="text-label w-full text-left px-3 py-2 rounded-t-xl transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current {open
					? 'md:shadow-lg'
					: 'rounded-b-xl'} {isActive ? 'bg-(--select-tint)' : open ? 'bg-white' : ''} {!open &&
				!isActive
					? 'hover:bg-(--select-tint)'
					: ''}"
				aria-expanded={open}
				aria-controls={open ? panelId : undefined}
				aria-label={isActive ? `${label} : ${count} ${count > 1 ? 'filtres' : 'filtre'}` : label}
				onclick={() => (open = !open)}
			>
				<div class="border-b-2 border-current flex items-center justify-between gap-3 pb-1">
					<div class="truncate-x">{label}</div>
					<div class="shrink-0 flex items-center gap-2">
						{#if isActive}
							<span
								class="text-caption font-bold min-w-5 h-5 px-1.5 inline-flex items-center justify-center rounded-full bg-(--select-color) text-white"
								aria-hidden="true"
							>
								<span class="block text-trim leading-none">{count}</span>
							</span>
						{/if}
						<IconChevron
							class="shrink-0 w-5.25 h-5.25 transition-transform {open ? 'rotate-180' : ''}"
						/>
					</div>
				</div>
			</button>

			{#if open}
				<div
					id={panelId}
					class="md:absolute md:inset-x-0 md:top-full md:z-20 pb-2 rounded-b-xl bg-white md:shadow-lg"
					transition:slide={panelSlide}
				>
					<fieldset class="pt-2">
						<legend class="sr-only">{label}</legend>
						{#each terms as term (term.slug)}
							{@render option(term, selected.includes(term.slug), () => toggleParent(term), false)}
							<!-- Sub-terms are listed right under their parent, selected or not. -->
							{#each term.children as child (child.slug)}
								{@render option(
									child,
									isChildSelected(term, child),
									() => toggleChild(term, child),
									true
								)}
							{/each}
						{/each}
					</fieldset>

					<button
						type="button"
						class="text-label w-full text-left px-3 py-1 transition-colors enabled:hover:bg-(--select-tint) disabled:opacity-35 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
						disabled={!isActive}
						aria-label="{clearLabel} : {label}"
						onclick={clear}
					>
						Effacer
					</button>
				</div>
			{/if}
		</div>
	</div>
{/if}
