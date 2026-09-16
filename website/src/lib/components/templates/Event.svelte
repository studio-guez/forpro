<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import EventProjectBody from '$lib/components/blocks/EventProjectBody.svelte';
	import SingleContentFooter from '$lib/components/blocks/SingleContentFooter.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { mergeTerms } from '$lib/utils/shared';
	import {
		formatEventDate,
		formatEventDateRange,
		formatEventTime,
		timeAttr,
		toDate
	} from '$lib/utils/date';
	import type { EventPage } from '$lib/interfaces/event';

	let { page }: { page: EventPage } = $props();

	const start = $derived(toDate(page.dateStart));
	// A dateEnd equal to dateStart is a single-day event, not a range.
	const end = $derived(page.dateEnd === page.dateStart ? null : toDate(page.dateEnd));
	const range = $derived(start && end ? formatEventDateRange(start, end) : null);
	const timeStart = $derived(formatEventTime(page.timeStart));
	const timeEnd = $derived(formatEventTime(page.timeEnd));

	const terms = $derived(
		mergeTerms({ programs: page.programs, resourcesTaxonomy: page.resourcesTaxonomy })
	);
</script>

<article class="space-y-12 lg:space-y-16">
	<EventProjectHeader
		title={page.title}
		subtitle={page.subtitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
		variant="event"
	>
		{#snippet before()}
			{#if page.parentPage}
				<BackLink parentPage={page.parentPage} />
			{/if}
		{/snippet}
		{#snippet meta()}
			{#if start}
				<div class="flex flex-col gap-1 mt-4.5 lg:mt-3">
					<p>
						{#if range}
							Du <strong><time datetime={page.dateStart}>{range[0]}</time></strong> au
							<strong><time datetime={page.dateEnd}>{range[1]}</time></strong>
						{:else}
							<strong><time datetime={page.dateStart}>{formatEventDate(start)}</time></strong>
						{/if}
					</p>
					{#if timeStart}
						<p class="flex items-center gap-1.5">
							<time datetime={timeAttr(page.timeStart)}>{timeStart}</time>
							{#if timeEnd}
								<span aria-hidden="true">–</span>
								<time datetime={timeAttr(page.timeEnd)}>{timeEnd}</time>
							{/if}
						</p>
					{/if}
					{#if page.location}
						<p>{page.location}</p>
					{/if}
				</div>
			{/if}

			{#if terms.length > 0}
				<div class="mt-4.5">
					<TermTags {terms} label="Tags" class="mt-2" />
				</div>
			{/if}
		{/snippet}
	</EventProjectHeader>

	<EventProjectBody blocks={page.body} title={page.title} />

	<SingleContentFooter parentPage={page.parentPage} title={page.title} />
</article>
