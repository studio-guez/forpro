<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import { tick } from 'svelte';
	import IconSearch from '$lib/components/svg/IconSearch.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import IconSpinner from '$lib/components/svg/IconSpinner.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import type { SearchGroup, SearchResponse, SearchResult } from '$lib/interfaces/search';

	interface Props {
		open: boolean;
		value: string;
	}

	let { open = $bindable(false), value = $bindable('') }: Props = $props();

	const TABS: { value: SearchGroup; label: string }[] = [
		{ value: 'all', label: 'Tout' },
		{ value: 'pages', label: 'Pages' },
		{ value: 'events', label: 'Événements' },
		{ value: 'projects', label: 'Projets' }
	];

	const NO_COUNTS: Record<SearchGroup, number> = { all: 0, pages: 0, events: 0, projects: 0 };

	let dialog = $state<HTMLDialogElement>();
	let input = $state<HTMLInputElement>();
	let scroller = $state<HTMLElement>();
	let sentinel = $state<HTMLElement>();

	let group = $state<SearchGroup>('all');
	let results = $state<SearchResult[]>([]);
	let counts = $state<Record<SearchGroup, number>>(NO_COUNTS);
	let total = $state(0);
	let hasMore = $state(false);
	let loading = $state(false);
	let loadingMore = $state(false);
	let failed = $state(false);
	// Query the current `results` belong to, used to highlight the matches.
	let matched = $state('');

	const query = $derived(value.trim());
	const isTooShort = $derived(query.length < 2);

	const close = () => {
		open = false;
	};

	const reset = () => {
		results = [];
		counts = NO_COUNTS;
		total = 0;
		hasMore = false;
		loading = false;
		loadingMore = false;
		failed = false;
		matched = '';
	};

	const search = async (
		q: string,
		target: SearchGroup,
		offset: number,
		signal?: AbortSignal
	): Promise<SearchResponse> => {
		const params = new URLSearchParams({ q, group: target, offset: String(offset) });
		const response = await fetch(`/api/search?${params}`, { signal });
		if (!response.ok) throw new Error(`${response.status} ${response.statusText}`);

		return response.json();
	};

	$effect(() => {
		if (!dialog) return;
		if (open && !dialog.open) {
			dialog.showModal();
			document.body.style.overflow = 'hidden';
			tick().then(() => {
				input?.focus();
				input?.setSelectionRange(value.length, value.length);
			});
		} else if (!open && dialog.open) {
			dialog.close();
		}
	});

	$effect(() => () => {
		document.body.style.overflow = '';
	});

	$effect(() => {
		if (!open || isTooShort) {
			reset();
			return;
		}

		const current = query;
		const currentGroup = group;
		loading = true;
		const controller = new AbortController();
		const timer = setTimeout(async () => {
			try {
				const data = await search(current, currentGroup, 0, controller.signal);
				results = data.results;
				counts = data.counts;
				total = data.total;
				hasMore = data.hasMore;
				matched = current;
				failed = false;
			} catch (error) {
				// A newer keystroke aborted this request: its own run owns the state.
				if (error instanceof DOMException && error.name === 'AbortError') return;
				results = [];
				total = 0;
				hasMore = false;
				failed = true;
			}
			loading = false;
			// Back to the top: the list now belongs to another query or tab.
			scroller?.scrollTo({ top: 0 });
		}, 250);

		return () => {
			clearTimeout(timer);
			controller.abort();
		};
	});

	const loadMore = async () => {
		if (loadingMore || loading || !hasMore) return;

		loadingMore = true;
		try {
			const data = await search(matched, group, results.length);
			results = [...results, ...data.results];
			counts = data.counts;
			total = data.total;
			hasMore = data.hasMore;
		} catch {
			// Stop pulling rather than looping on a failing endpoint.
			hasMore = false;
		}
		loadingMore = false;
	};

	// Infinite scroll: the sentinel sits under the last row, inside the scroller.
	$effect(() => {
		if (!scroller || !sentinel || !hasMore) return;

		let timer: ReturnType<typeof setTimeout> | undefined;
		const observer = new IntersectionObserver(
			(entries) => {
				clearTimeout(timer);
				// Debounced: a fast flick past the sentinel must not queue a fetch per page.
				if (entries[0].isIntersecting) timer = setTimeout(loadMore, 200);
			},
			{ root: scroller, rootMargin: '200px' }
		);
		observer.observe(sentinel);

		return () => {
			clearTimeout(timer);
			observer.disconnect();
		};
	});

	// The FAQ page filters its questions on `?q=`, so a FAQ hit can land the
	// visitor directly on the matching question instead of the whole list.
	const resultUrl = (result: SearchResult): string =>
		result.type === 'faq' ? `${result.url}?q=${encodeURIComponent(query)}` : result.url;

	// Accent- and case-folded copy of the text. Folding happens per UTF-16 unit
	// so indexes stay aligned with the original string and can be sliced back.
	const fold = (text: string): string =>
		text
			.split('')
			.map((char) => {
				const stripped = char
					.normalize('NFD')
					.replace(/\p{Diacritic}/gu, '')
					.toLowerCase();
				return stripped.length === 1 ? stripped : char;
			})
			.join('');

	const words = $derived(
		fold(matched)
			.split(/\s+/)
			.filter((word) => word.length >= 2)
	);

	// Splits a text into consecutive matched/unmatched segments so the matches
	// can be wrapped in <mark> without injecting HTML.
	const highlight = (text: string): { text: string; match: boolean }[] => {
		if (text === '' || words.length === 0) return [{ text, match: false }];

		const haystack = fold(text);
		const ranges: [number, number][] = [];
		for (const word of words) {
			let from = haystack.indexOf(word);
			while (from !== -1) {
				ranges.push([from, from + word.length]);
				from = haystack.indexOf(word, from + word.length);
			}
		}
		if (ranges.length === 0) return [{ text, match: false }];

		ranges.sort((a, b) => a[0] - b[0]);
		const parts: { text: string; match: boolean }[] = [];
		let cursor = 0;
		for (const [start, end] of ranges) {
			if (end <= cursor) continue;
			const from = Math.max(start, cursor);
			if (from > cursor) parts.push({ text: text.slice(cursor, from), match: false });
			parts.push({ text: text.slice(from, end), match: true });
			cursor = end;
		}
		if (cursor < text.length) parts.push({ text: text.slice(cursor), match: false });

		return parts;
	};
</script>

<dialog
	bind:this={dialog}
	onclose={() => {
		open = false;
		group = 'all';
		document.body.style.overflow = '';
	}}
	onclick={(event) => {
		if (event.target === dialog) close();
	}}
	aria-label="Recherche sur le site"
	class="fixed top-0 left-1/2 mt-4 md:mt-24 -translate-x-1/2 w-[min(64rem,calc(100vw-1.5rem))] max-h-[calc(100dvh-2rem)] md:max-h-[calc(100dvh-12rem)] hidden open:flex flex-col overflow-hidden rounded-3xl bg-white p-0 text-blue shadow-2xl backdrop:bg-black/40"
>
	<div class="flex items-center gap-x-2 px-5 md:px-8 py-4 border-b-2 border-grey-light shrink-0">
		<IconSearch class="shrink-0" />
		<input
			bind:this={input}
			bind:value
			oninput={() => (group = 'all')}
			type="text"
			placeholder="Rechercher..."
			aria-label="Rechercher sur le site"
			class="text-body-2 font-bold text-blue bg-transparent border-0 grow min-w-0 placeholder:text-blue/50 focus:ring-0 focus:outline-none"
		/>
		<button
			type="button"
			onclick={close}
			class="shrink-0 p-2 rounded-full hover:bg-blue hover:text-white transition-colors"
			aria-label="Fermer la recherche"
		>
			<IconClose class="shrink-0 w-6 h-auto" width="24" height="25" />
		</button>
	</div>

	{#if !isTooShort && counts.all > 0}
		<div
			role="group"
			aria-label="Filtrer par type de contenu"
			class="flex flex-wrap gap-2 px-5 md:px-8 py-3 border-b-2 border-grey-light shrink-0 overflow-x-auto"
		>
			{#each TABS as tab (tab.value)}
				<button
					type="button"
					onclick={() => (group = tab.value)}
					disabled={counts[tab.value] === 0}
					aria-pressed={group === tab.value}
					class="text-label font-bold whitespace-nowrap rounded-full border-2 px-4 py-1 transition-colors disabled:opacity-40 disabled:cursor-not-allowed {group ===
					tab.value
						? 'bg-blue border-blue text-white'
						: 'border-grey-light hover:border-blue'}"
				>
					{tab.label}
					<span class="font-normal">({counts[tab.value]})</span>
				</button>
			{/each}
		</div>
	{/if}

	<div bind:this={scroller} class="overflow-y-auto px-5 md:px-8 py-4">
		<p class="sr-only" aria-live="polite">
			{#if loading}
				Recherche en cours
			{:else if !isTooShort}
				{total} résultat{total > 1 ? 's' : ''}
			{/if}
		</p>

		{#if isTooShort}
			<p class="text-label text-grey-dark py-4">Saisissez au moins 2 caractères.</p>
		{:else if failed}
			<p class="text-label text-red py-4">La recherche est momentanément indisponible.</p>
		{:else if results.length === 0}
			<p class="text-label text-grey-dark py-4">
				{loading ? 'Recherche en cours…' : `Aucun résultat pour « ${query} ».`}
			</p>
		{:else}
			<p class="text-label text-grey-dark mb-2">
				{total} résultat{total > 1 ? 's' : ''}
			</p>
			<ul class="flex flex-col divide-y-2 divide-grey-light">
				{#each results as result (result.id)}
					<li>
						<a
							href={resultUrl(result)}
							onclick={close}
							class="flex items-start gap-x-4 py-4 group focus:outline-none focus-visible:ring-2 focus-visible:ring-blue rounded-xl px-2 -mx-2 hover:bg-grey-light/40 transition-colors"
						>
							{#if result.cover}
								<Img
									image={result.cover}
									alt=""
									sizes="(min-width: 768px) 8rem, 4rem"
									class="shrink-0 w-16 h-16 md:w-32 md:h-32 rounded-xl object-cover bg-grey-light"
								/>
							{:else}
								<div class="shrink-0 w-16 h-16 md:w-32 md:h-32 rounded-xl bg-grey-light" />
							{/if}
							<div class="min-w-0">
								<p class="text-caption text-grey-dark">{result.typeLabel}</p>
								<p
									class="text-body-2 font-bold underline decoration-transparent group-hover:decoration-current transition-colors"
								>
									{#each highlight(result.title) as part, index (index)}
										{#if part.match}<mark class="bg-green/60 text-blue">{part.text}</mark
											>{:else}{part.text}{/if}
									{/each}
								</p>
								{#if result.excerpt}
									<p class="text-label text-grey-dark mt-1">
										{#each highlight(result.excerpt) as part, index (index)}
											{#if part.match}<mark class="bg-green/60 text-grey-dark">{part.text}</mark
												>{:else}{part.text}{/if}
										{/each}
									</p>
								{/if}
							</div>
						</a>
					</li>
				{/each}
			</ul>
			<div bind:this={sentinel} class="h-px" aria-hidden="true"></div>
			{#if hasMore}
				<p class="flex justify-center py-4 text-grey-dark">
					<IconSpinner class="motion-safe:animate-spin" width={32} height={32} />
					<span class="sr-only">Chargement…</span>
				</p>
			{/if}
		{/if}
	</div>
</dialog>
