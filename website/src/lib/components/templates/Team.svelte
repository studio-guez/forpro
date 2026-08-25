<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import IconApprentice from '$lib/components/svg/IconApprentice.svelte';
	import IconEmployee from '$lib/components/svg/IconEmployee.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import type { TeamPage } from '$lib/interfaces/team';

	let { page }: { page: TeamPage } = $props();
</script>

<PageHeader {page} />

{#each page.sections as section, sectionIndex (sectionIndex)}
	<section
		aria-labelledby="team-section-{sectionIndex}"
		class="grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-9"
	>
		<h2 id="team-section-{sectionIndex}" class="text-label font-bold">{section.title}</h2>

		<ul class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
			{#each section.members as member, memberIndex (memberIndex)}
				<li>
					<p class="text-label font-bold">{member.name}</p>

					{#if member.role}
						<p class="text-label flex items-center gap-2 mt-1">
							{#if member.status === 'apprenti'}
								<IconApprentice width={18} height={18} class="shrink-0" />
							{:else}
								<IconEmployee width={18} height={18} class="shrink-0" />
							{/if}
							{member.role}
						</p>
					{/if}

					{#if member.linkedin}
						<a
							href={member.linkedin}
							target="_blank"
							rel="noopener noreferrer"
							class="text-label flex items-center gap-2 mt-1 underline hover:opacity-50 transition-opacity"
						>
							<IconLink width={18} height={18} class="shrink-0" />
							LinkedIn
						</a>
					{/if}
				</li>
			{/each}
		</ul>
	</section>
{/each}

<Blocks blocks={page.body} />
