<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import ExpandableSection from '$lib/components/ui/ExpandableSection.svelte';
	import IconApprentice from '$lib/components/svg/IconApprentice.svelte';
	import IconEmployee from '$lib/components/svg/IconEmployee.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { LABELLED_SECTION, LABELLED_SECTION_ITEMS } from '$lib/utils/sectionStyles';
	import type { TeamPage } from '$lib/interfaces/team';

	let { page }: { page: TeamPage } = $props();

	// The first section starts open, the others are unfolded on demand.
	let openSections = $state<number[]>([0]);
	const isSectionOpen = (index: number): boolean => openSections.includes(index);
	const setSectionOpen = (index: number, open: boolean): void => {
		openSections = open ? [...openSections, index] : openSections.filter((i) => i !== index);
	};
</script>

<PageHeader {page} />

<section aria-label="Membres de l'équipe" class="px-base pb-12 lg:pb-16">
	{#each page.sections as section, sectionIndex (sectionIndex)}
		<ExpandableSection
			id="team-section-{sectionIndex}"
			title={section.title}
			class="mt-12 lg:mt-18"
			bind:open={() => isSectionOpen(sectionIndex), (value) => setSectionOpen(sectionIndex, value)}
		>
			<div class="mt-9 lg:mt-12 space-y-12 lg:space-y-16">
				{#each section.groups as group, groupIndex (groupIndex)}
					{@const groupId = `team-group-${sectionIndex}-${groupIndex}`}
					<section aria-labelledby={groupId} class={LABELLED_SECTION}>
						<h3 id={groupId} class="text-body-2 font-bold">{group.title}</h3>

						<ul class={LABELLED_SECTION_ITEMS}>
							{#each group.members as member, memberIndex (memberIndex)}
								<li>
									<p class="text-body-2 font-bold">{member.name}</p>

									{#if member.role}
										<p class="text-body-2 flex items-center gap-2 mt-1">
											{#if member.status === 'apprenti'}
												<IconApprentice class="shrink-0 w-4.5 h-4.5" />
											{:else}
												<IconEmployee class="shrink-0 w-4.5 h-4.5" />
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
											<IconLink class="shrink-0 w-4.5 h-4.5" />
											LinkedIn
										</a>
									{/if}
								</li>
							{/each}
						</ul>
					</section>
				{/each}
			</div>
		</ExpandableSection>
	{/each}
</section>

<Blocks blocks={page.body} />
