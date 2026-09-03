<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import Card from '$lib/components/ui/Card.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { CARD, cell, toSizes } from '$lib/utils/imgSizes';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { TAG_BASE, tagColorClasses, tagSizeClasses } from '$lib/utils/tagStyles';
	import type { CompanyBadge, FactoryLabPage } from '$lib/interfaces/factoryLab';

	let { page }: { page: FactoryLabPage } = $props();

	const companiesModule = $derived(page.companiesModule);

	const companySizes = toSizes(cell(CARD, { 0: 1, 1024: 2 }, 1.5));

	// Same pill as the taxonomy tags, in its filled `sm` variant: the colour comes from the
	// `--term-color` custom property set on the list item.
	const badgeClass = [
		TAG_BASE,
		tagSizeClasses.sm,
		tagColorClasses('sm', false),
		'inline-flex items-center gap-1.5'
	].join(' ');

	// This module is specific to the FactoryLab page: its colours are fixed by design and
	// deliberately ignore the page theme and the module variant.
	const trainingColor = 'var(--color-pink)';
	const availabilityColor = 'var(--color-teal)';
</script>

<PageHeader {page} />

{#snippet badgeContent(badge: CompanyBadge)}
	{#if badge.url}
		<IconLink class="shrink-0 w-4 h-4" />
	{/if}
	{badge.label}
{/snippet}

{#snippet badgeList(label: string, badges: CompanyBadge[], badgeColor: string)}
	{#if badges.length > 0}
		<p class="text-label font-bold mt-5">{label}</p>
		<ul class="flex flex-wrap items-center gap-y-1.5 gap-x-1.5 mt-2">
			{#each badges as badge, badgeIndex (badgeIndex)}
				<li style:--term-color={badgeColor}>
					{#if badge.url}
						<a
							href={badge.url}
							target="_blank"
							rel="noopener noreferrer"
							class="{badgeClass} hover:opacity-50 transition-opacity"
						>
							{@render badgeContent(badge)}
						</a>
					{:else}
						<span class={badgeClass}>
							{@render badgeContent(badge)}
						</span>
					{/if}
				</li>
			{/each}
		</ul>
	{/if}
{/snippet}

{#if companiesModule.companies.length > 0}
	<Card
		background="var(--color-white)"
		color="var(--color-black)"
		title={companiesModule.title}
		shortDesc={companiesModule.intro}
		titleBackground="var(--color-teal)"
		titleColor="var(--color-white)"
	>
		<ul class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-16 mt-12">
			{#each companiesModule.companies as company, companyIndex (companyIndex)}
				<li>
					<div class="rounded-2xl overflow-hidden aspect-16/10 bg-grey-light">
						{#if company.image}
							<Img
								image={company.image}
								alt={company.image.alt ?? company.title}
								sizes={companySizes}
								class="w-full h-full object-cover"
							/>
						{/if}
					</div>

					<h3 class="text-h4 mt-6" style:color="var(--color-teal)">
						{#if company.url}
							<a
								href={company.url}
								target="_blank"
								rel="noopener noreferrer"
								class="flex items-center gap-2 hover:opacity-50 transition-opacity"
							>
								<IconLink class="shrink-0 w-6 h-6" />
								{company.title}
							</a>
						{:else}
							{company.title}
						{/if}
					</h3>

					{#if company.description}
						<div class="prose text-body-2 mt-3">
							{@html company.description}
						</div>
					{/if}

					{@render badgeList(companiesModule.labels.trainings, company.trainings, trainingColor)}
					{@render badgeList(
						companiesModule.labels.availability,
						company.availability,
						availabilityColor
					)}

					{#if company.followUps.length > 0}
						<p class="text-label font-bold mt-5">{companiesModule.labels.followUp}</p>
						<ul class="text-label list-disc list-inside mt-2">
							{#each company.followUps as followUp, followUpIndex (followUpIndex)}
								<li>{followUp}</li>
							{/each}
						</ul>
					{/if}
				</li>
			{/each}
		</ul>
	</Card>
{/if}

<Blocks blocks={page.body} theme={page.theme} />
