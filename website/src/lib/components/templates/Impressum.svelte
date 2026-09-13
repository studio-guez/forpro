<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { LABELLED_SECTION, LABELLED_SECTION_ITEMS } from '$lib/utils/sectionStyles';
	import type { ImpressumPage } from '$lib/interfaces/impressum';

	let { page }: { page: ImpressumPage } = $props();

	const partnerSizes = toSizes(
		cell(cell(PAGE, { 0: 1, 1024: 4 }, 1.5, 3), { 0: 1, 640: 2, 1024: 3 }, 1.5)
	);
</script>

<BasicHeader title={page.title} />

{#if page.partners.length > 0}
	<section
		aria-labelledby="impressum-partners"
		class="px-base grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-9"
	>
		<h2 id="impressum-partners" class="text-label font-bold">{page.partnersTitle}</h2>

		<ul class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
			{#each page.partners as partner, partnerIndex (partnerIndex)}
				<li>
					{#if partner.image}
						<Img
							image={partner.image}
							alt={partner.image.alt ?? partner.title}
							sizes={partnerSizes}
							class="w-full aspect-4/3 object-cover rounded-lg bg-grey-light mb-3"
						/>
					{/if}

					<p class="text-label font-bold">{partner.title}</p>

					{#if partner.link}
						<a
							href={partner.link.url}
							target="_blank"
							rel="noopener noreferrer"
							class="text-label flex items-center gap-2 mt-1 underline hover:opacity-50 transition-opacity"
						>
							<IconLink class="shrink-0 w-4.5 h-4.5" />
							{partner.link.label}
						</a>
					{/if}
				</li>
			{/each}
		</ul>
	</section>
{/if}

{#each page.sections as section, sectionIndex (sectionIndex)}
	<section aria-labelledby="impressum-section-{sectionIndex}" class="px-base {LABELLED_SECTION}">
		<h2 id="impressum-section-{sectionIndex}" class="text-label font-bold">{section.title}</h2>

		<dl class={LABELLED_SECTION_ITEMS}>
			{#each section.credits as credit, creditIndex (creditIndex)}
				<div>
					<dt class="text-label font-bold">{credit.role}</dt>

					{#each credit.names as name, nameIndex (nameIndex)}
						<dd class="text-label mt-1">{name}</dd>
					{/each}

					{#if credit.link}
						<dd class="mt-1">
							<a
								href={credit.link.url}
								target="_blank"
								rel="noopener noreferrer"
								class="text-label flex items-center gap-2 underline hover:opacity-50 transition-opacity"
							>
								<IconLink class="shrink-0 w-4.5 h-4.5" />
								{credit.link.label}
							</a>
						</dd>
					{/if}
				</div>
			{/each}
		</dl>
	</section>
{/each}

<Blocks blocks={page.body} />
