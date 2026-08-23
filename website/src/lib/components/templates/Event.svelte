<script lang="ts">
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import EventProjectMedia from '$lib/components/blocks/EventProjectMedia.svelte';
	import EventProjectBlocks from '$lib/components/blocks/EventProjectBlocks.svelte';
	import EventProjectLinks from '$lib/components/blocks/EventProjectLinks.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { timeAttr, toDate } from '$lib/utils/date';
	import type { EventPage } from '$lib/interfaces/event';

	let { page }: { page: EventPage } = $props();

	const dateFormat = new Intl.DateTimeFormat('fr-CH', { dateStyle: 'long' });
	const timeFormat = new Intl.DateTimeFormat('fr-CH', { hour: '2-digit', minute: '2-digit' });

	const start = $derived(toDate(page.dateStart, page.timeStart));
	const end = $derived(toDate(page.dateEnd, page.timeEnd));
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
						<time datetime={page.dateStart}>{dateFormat.format(start)}</time>{#if page.timeStart}, <time
								datetime={timeAttr(page.timeStart)}>{timeFormat.format(start)}</time
							>{/if}
						{#if end}
							<span aria-hidden="true"> – </span>
							<time datetime={page.dateEnd}>{dateFormat.format(end)}</time>{#if page.timeEnd}, <time
									datetime={timeAttr(page.timeEnd)}>{timeFormat.format(end)}</time
								>{/if}
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
