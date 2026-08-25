<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import Card from '$lib/components/ui/Card.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import type { CompanyBadge, FactoryLabPage } from '$lib/interfaces/factoryLab';

	let { page }: { page: FactoryLabPage } = $props();

	const companiesModule = $derived(page.companiesModule);
	const colors = $derived(cardThemeColors[page.theme][companiesModule.variant]);

	// The module is drawn on the page background by default (as designed) and only
	// gets the filled card treatment on the inverted variant.
	const onColor = $derived(companiesModule.variant === 'inverted');

	const badgeClass =
		'text-caption inline-flex items-center gap-1.5 rounded-full px-3 py-1 leading-tight';

	const trainingBadge = $derived({ bg: colors.bgContrast, text: 'var(--color-white)' });
	const availabilityBadge = $derived(
		onColor
			? { bg: 'var(--color-white)', text: colors.bg }
			: { bg: colors.bg, text: 'var(--color-white)' }
	);
</script>

<PageHeader {page} />

{#snippet badgeContent(badge: CompanyBadge)}
	{#if badge.url}
		<IconLink width={16} height={16} class="shrink-0" />
	{/if}
	{badge.label}
{/snippet}

{#snippet badgeList(label: string, badges: CompanyBadge[], badgeColors: { bg: string; text: string })}
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
		background={onColor ? colors.bg : null}
		color={onColor ? colors.text : null}
		title={companiesModule.title}
		shortDesc={companiesModule.intro}
		titleBackground={onColor ? 'var(--color-white)' : colors.bg}
		titleColor={onColor ? colors.bg : 'var(--color-white)'}
	>
		<ul class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-16 mt-12">
			{#each companiesModule.companies as company, companyIndex (companyIndex)}
				<li>
					<div class="rounded-2xl overflow-hidden aspect-16/10 bg-grey-light">
						{#if company.image}
							<Img
								image={company.image}
								alt={company.image.alt ?? company.title}
								sizes="(min-width: 768px) 50vw, 100vw"
								class="w-full h-full object-cover"
							/>
						{/if}
					</div>

					<h3 class="text-h4 mt-6" style:color={onColor ? undefined : colors.bg}>
						{#if company.url}
							<a
								href={company.url}
								target="_blank"
								rel="noopener noreferrer"
								class="flex items-center gap-2 hover:opacity-50 transition-opacity"
							>
								<IconLink width={24} height={24} class="shrink-0" />
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
