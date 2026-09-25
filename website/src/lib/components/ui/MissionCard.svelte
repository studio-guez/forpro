<script lang="ts">
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { MissionCard } from '$lib/interfaces/missions';

	interface Props {
		mission: MissionCard;
		headingTag?: 'h2' | 'h3';
	}

	let { mission, headingTag = 'h2' }: Props = $props();
</script>

<article class="relative h-full flex flex-col gap-3">
	<svelte:element this={headingTag} class="text-h4">
		<a href={mission.url} class="hover:opacity-50 transition after:absolute after:inset-0">
			{mission.title}
		</a>
	</svelte:element>

	<p class="text-label">
		{#if mission.date}
			{mission.date}
			<span aria-hidden="true">·</span>
		{/if}
		{mission.location}
	</p>

	{#if mission.shortDesc}
		<div class="prose text-body-2">{@html mission.shortDesc}</div>
	{/if}

	<TermTags terms={mission.terms} label="Domaines" size="md" />
</article>
