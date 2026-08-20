<script lang="ts">
	import EventProjectHeader from '$lib/components/EventProjectHeader.svelte';
	import EventProjectMedia from '$lib/components/EventProjectMedia.svelte';
	import EventProjectBlocks from '$lib/components/EventProjectBlocks.svelte';
	import EventProjectLinks from '$lib/components/EventProjectLinks.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { EventPage } from '$lib/interfaces/event';

	let { page }: { page: EventPage } = $props();

	const dateFormat = new Intl.DateTimeFormat('fr-CH', {
		dateStyle: 'long',
		timeStyle: 'short'
	});

	// Parse the CMS `YYYY-MM-DDTHH:mm` (local) datetime; invalid/empty -> null.
	const toDate = (value: string | null): Date | null => {
		if (!value) return null;
		const date = new Date(value);
		return Number.isNaN(date.getTime()) ? null : date;
	};

	const start = $derived(toDate(page.dateStart));
	const end = $derived(toDate(page.dateEnd));
</script>

<article class="py-12 md:py-16 space-y-12 md:space-y-16">
	<EventProjectHeader
		title={page.title}
		subtitle={page.subtitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
	>
		{#snippet meta()}
			{#if start}
				<div>
					<h2 class="text-label text-teal">Dates</h2>
					<p class="text-body-2 mt-1">
						<time datetime={page.dateStart}>{dateFormat.format(start)}</time>
						{#if end}
							<span aria-hidden="true"> – </span>
							<time datetime={page.dateEnd}>{dateFormat.format(end)}</time>
						{/if}
					</p>
				</div>
			{/if}

			{#if page.domains.length > 0}
				<div>
					<h2 class="text-label text-teal">Domaines</h2>
					<TermTags terms={page.domains} label="Domaines" class="mt-2" />
				</div>
			{/if}

			{#if page.eventThemes.length > 0}
				<div>
					<h2 class="text-label text-teal">Thématiques</h2>
					<TermTags terms={page.eventThemes} label="Thématiques" class="mt-2" />
				</div>
			{/if}
		{/snippet}
	</EventProjectHeader>

	<EventProjectMedia medias={page.medias} embedVideos={page.embedVideos} title={page.title} />

	<EventProjectBlocks blocks={page.blocks} />

	<EventProjectLinks links={page.externalLinks} />
</article>
