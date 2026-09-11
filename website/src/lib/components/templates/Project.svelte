<script lang="ts">
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import EventProjectMedia from '$lib/components/blocks/EventProjectMedia.svelte';
	import EventProjectVideos from '$lib/components/blocks/EventProjectVideos.svelte';
	import EventProjectBlocks from '$lib/components/blocks/EventProjectBlocks.svelte';
	import EventProjectLinks from '$lib/components/blocks/EventProjectLinks.svelte';
	import SingleContentFooter from '$lib/components/blocks/SingleContentFooter.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { ProjectPage } from '$lib/interfaces/project';

	let { page }: { page: ProjectPage } = $props();
</script>

<article class="py-12 lg:py-16 space-y-12 lg:space-y-16">
	<EventProjectHeader
		title={page.title}
		subtitle={page.subtitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
		variant="project"
	>
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

			{#if page.categories.length > 0}
				<ul class="mt-4.5 lg:mt-3">
					{#each page.categories as category, index (index)}
						<li class="inline font-bold">
							{category.title}{index < page.categories.length - 1 ? ', ' : ''}
						</li>
					{/each}
				</ul>
			{/if}

			{#if page.programs.length > 0}
				<div class="mt-4.5">
					<TermTags terms={page.programs} label="Programmes" class="mt-2" />
				</div>
			{/if}
		{/snippet}
	</EventProjectHeader>

	<EventProjectMedia medias={page.medias} title={page.title} />

	<EventProjectVideos videos={page.videos} title={page.title} />

	<EventProjectBlocks blocks={page.blocks} />

	<EventProjectLinks links={page.externalLinks} />

	<SingleContentFooter
		parentPage={page.parentPage}
		title={page.title}
		color="var(--color-orange)"
	/>
</article>
