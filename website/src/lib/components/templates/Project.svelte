<script lang="ts">
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import EventProjectMedia from '$lib/components/blocks/EventProjectMedia.svelte';
	import EventProjectBlocks from '$lib/components/blocks/EventProjectBlocks.svelte';
	import EventProjectLinks from '$lib/components/blocks/EventProjectLinks.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { ProjectPage } from '$lib/interfaces/project';

	let { page }: { page: ProjectPage } = $props();
</script>

<article class="py-12 md:py-16 space-y-12 md:space-y-16">
	<EventProjectHeader
		title={page.title}
		subtitle={page.subtitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
	>
		{#snippet meta()}
			{#if page.programs.length > 0}
				<div>
					<h2 class="text-label text-teal">Programmes</h2>
					<TermTags terms={page.programs} label="Programmes" class="mt-2" />
				</div>
			{/if}

			{#if page.categories.length > 0}
				<div>
					<h2 class="text-label text-teal">Catégories</h2>
					<TermTags terms={page.categories} label="Catégories" class="mt-2" />
				</div>
			{/if}

			{#if page.collectiveName || page.collectiveMembers.length > 0}
				<div>
					<h2 class="text-label text-teal">Collectif</h2>
					{#if page.collectiveName}
						<p class="text-body-2 mt-1">{page.collectiveName}</p>
					{/if}
					{#if page.collectiveMembers.length > 0}
						<ul class="text-body-2 text-grey-dark mt-1">
							{#each page.collectiveMembers as member, index (index)}
								<li>{member.name}</li>
							{/each}
						</ul>
					{/if}
				</div>
			{/if}
		{/snippet}
	</EventProjectHeader>

	<EventProjectMedia medias={page.medias} embedVideos={page.embedVideos} title={page.title} />

	<EventProjectBlocks blocks={page.blocks} />

	<EventProjectLinks links={page.externalLinks} />
</article>
