<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeaderCompact from '$lib/components/blocks/PageHeaderCompact.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import type { ImpressumPage } from '$lib/interfaces/impressum';

	let { page }: { page: ImpressumPage } = $props();
</script>

<PageHeaderCompact title={page.title} />

{#if page.partners.length > 0}
	<section
		aria-labelledby="impressum-partners"
		class="grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-9"
	>
		<h2 id="impressum-partners" class="text-label font-bold">{page.partnersTitle}</h2>

		<ul class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
			{#each page.partners as partner, partnerIndex (partnerIndex)}
				<li>
					{#if partner.image}
						<Img
							image={partner.image}
							alt={partner.image.alt ?? partner.title}
							sizes="(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw"
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
							<IconLink width={18} height={18} class="shrink-0" />
							{partner.link.label}
						</a>
					{/if}
				</li>
			{/each}
		</ul>
	</section>
{/if}

{#each page.sections as section, sectionIndex (sectionIndex)}
	<section
		aria-labelledby="impressum-section-{sectionIndex}"
		class="grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-9"
	>
		<h2 id="impressum-section-{sectionIndex}" class="text-label font-bold">{section.title}</h2>

		<dl class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
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
								<IconLink width={18} height={18} class="shrink-0" />
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
