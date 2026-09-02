<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import Card from '$lib/components/ui/Card.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { CARD, cell, toSizes } from '$lib/utils/imgSizes';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { getThemeColors } from '$lib/utils/themeColors';
	import type { CompanyBadge, FactoryLabPage } from '$lib/interfaces/factoryLab';

	let { page }: { page: FactoryLabPage } = $props();

	const companiesModule = $derived(page.companiesModule);
	const colors = $derived(getThemeColors(page.theme, companiesModule.variant));

	const companySizes = toSizes(cell(CARD, { 0: 1, 1024: 2 }, 1.5));

	const badgeClass =
		'text-caption inline-flex items-center gap-1.5 rounded-full px-3 py-1 leading-tight';

	const trainingBadge = $derived({ bg: colors.bgContrast, text: 'var(--color-white)' });
	const availabilityBadge = $derived(
		colors.onDark
			? { bg: 'var(--color-white)', text: colors.surface }
			: { bg: colors.surface, text: colors.surfaceText }
	);
</script>

<PageHeader {page} />

{#snippet badgeContent(badge: CompanyBadge)}
	{#if badge.url}
		<IconLink class="shrink-0 w-4 h-4" />
	{/if}
	{badge.label}
{/snippet}

{#snippet badgeList(
	label: string,
	badges: CompanyBadge[],
	badgeColors: { bg: string; text: string }
)}
	{#if badges.length > 0}
		<p class="text-label font-bold mt-5">{label}</p>
		<ul class="flex flex-wrap items-center gap-2 mt-2">
			{#each badges as badge, badgeIndex (badgeIndex)}
				<li
					style:background-color={badgeColors.bg}
					style:color={badgeColors.text}
					class="rounded-full"
				>
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
		background={colors.bg}
		color={colors.text}
		title={companiesModule.title}
		shortDesc={companiesModule.intro}
		titleBackground={colors.titleBackground}
		titleColor={colors.title}
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

					<h3 class="text-h4 mt-6">
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

					{@render badgeList(companiesModule.labels.trainings, company.trainings, trainingBadge)}
					{@render badgeList(
						companiesModule.labels.availability,
						company.availability,
						availabilityBadge
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
