<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import EventProjectBody from '$lib/components/blocks/EventProjectBody.svelte';
	import SingleContentFooter from '$lib/components/blocks/SingleContentFooter.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { mergeTerms } from '$lib/utils/shared';
	import type { ProjectPage } from '$lib/interfaces/project';

	let { page }: { page: ProjectPage } = $props();

	const terms = $derived(
		mergeTerms({ programs: page.programs, sectors: page.sectors, categories: page.categories })
	);
</script>

<article class="space-y-12 lg:space-y-16">
	<EventProjectHeader
		title={page.title}
		subtitle={page.subtitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
		variant="project"
	>
		{#snippet before()}
			{#if page.parentPage}
				<BackLink parentPage={page.parentPage} color="var(--color-orange)" />
			{/if}
		{/snippet}
		{#snippet meta()}
			{#if page.collectiveName || page.collectiveMembers.length > 0}
				<div class="flex gap-3 mt-4.5 lg:mt-3">
					{#if page.collectiveName}
						<strong>{page.collectiveName}</strong>
						{#if page.collectiveMembers.length > 0}
							<span aria-hidden="true">·</span>
						{/if}
					{/if}
					{#if page.collectiveMembers.length > 0}
						<ul class="flex gap-3">
							{#each page.collectiveMembers as member, index (index)}
								<li class="inline">
									{member.name}{index < page.collectiveMembers.length - 1 ? ', ' : ''}
								</li>
							{/each}
						</ul>
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

	<SingleContentFooter
		parentPage={page.parentPage}
		title={page.title}
		color="var(--color-orange)"
	/>
</article>
