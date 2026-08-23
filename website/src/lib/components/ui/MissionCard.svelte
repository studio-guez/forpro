<script lang="ts">
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { formatShortDate, toDate } from '$lib/utils/date';
	import type { MissionCard } from '$lib/interfaces/missions';

	interface Props {
		mission: MissionCard;
		headingTag?: 'h2' | 'h3';
	}

	let { mission, headingTag = 'h2' }: Props = $props();

	const date = $derived(toDate(mission.date));
</script>

<article class="relative h-full flex flex-col gap-3 text-blue">
	<svelte:element this={headingTag} class="text-h4">
		<a href={mission.url} class="hover:opacity-70 transition after:absolute after:inset-0">
			{mission.title}
		</a>
	</svelte:element>

	<p class="text-label text-grey-dark">
		<time datetime={mission.date}>{date ? formatShortDate(date) : mission.date}</time>
		<span aria-hidden="true">·</span>
		{mission.location}
	</p>

	{#if mission.shortDesc}
		<div class="prose text-body-2 text-grey-dark">{@html mission.shortDesc}</div>
	{/if}

	<TermTags terms={mission.terms} label="Domaines" class="mt-auto pt-2" />
</article>
