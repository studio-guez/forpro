<script lang="ts">
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { CTA_BASE, CTA_LABEL, ctaIconSizeClasses, ctaSizeClasses } from '$lib/utils/ctaStyles';
	import { CARD_SMALL, cell, toSizes } from '$lib/utils/imgSizes';
	import type { InfosPratiquesPage } from '$lib/interfaces/infosPratiques';
	import type { PageCta } from '$lib/interfaces/page';

	let { page }: { page: InfosPratiquesPage } = $props();

	const halfPlateSizes = toSizes(cell(CARD_SMALL, { 0: 1, 1024: 2 }, 1.5));

	const MAP_LINK_LABEL = 'Ouvrir dans Google Maps';

	const hasHours = $derived(!!(page.openingHoursTitle || page.openingHoursContent));
	const hasFoodlab = $derived(
		!!(page.foodlabOpeningHoursTitle || page.foodlabImage || page.foodlabCta)
	);
	const hasAccess = $derived(!!(page.accessContent || page.mapImage || page.mapUrl));
	const mapCta: PageCta | null = $derived(
		page.mapUrl
			? { label: MAP_LINK_LABEL, url: page.mapUrl, icon: 'arrow', target: '_blank' }
			: null
	);
</script>

<BasicHeader title={page.title} />

{#if hasHours || hasFoodlab}
	<section
		aria-labelledby={page.openingHoursTitle
			? 'opening-hours-title'
			: page.foodlabOpeningHoursTitle
				? 'foodlab-hours-title'
				: undefined}
		class="lg:px-9"
	>
		<div class="grid lg:grid-cols-2 gap-y-9 gap-x-6 items-stretch text-blue">
			{#if hasHours}
				<div class="px-5 lg:pl-15 xl:pl-30 lg:pr-8 lg:py-12">
					{#if page.openingHoursTitle}
						<h2 id="opening-hours-title" class="text-h4">{page.openingHoursTitle}</h2>
					{/if}
					{#if page.openingHoursContent}
						<div class="prose text-body-2 mt-6">
							<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
							{@html page.openingHoursContent}
						</div>
					{/if}
				</div>
			{/if}

			{#if hasFoodlab}
				<div class="lg:col-start-2">
					<div class="relative overflow-hidden rounded-3xl bg-blue text-white h-full">
						{#if page.foodlabImage}
							<Img
								image={page.foodlabImage}
								alt={page.foodlabImage.alt ?? page.foodlabOpeningHoursTitle ?? ''}
								sizes={halfPlateSizes}
								class="absolute inset-0 w-full h-full object-cover"
							/>
							<div
								aria-hidden="true"
								class="absolute inset-0 bg-linear-to-b from-black/30 via-black/0 to-black/30"
							></div>
						{/if}

						<div class="relative flex h-full flex-col justify-between p-5 lg:p-8">
							{#if page.foodlabOpeningHoursTitle}
								<h2 id="foodlab-hours-title" class="text-h4">{page.foodlabOpeningHoursTitle}</h2>
							{/if}
							{#if page.foodlabCta}
								<CtaLink cta={page.foodlabCta} inverted class="self-end mt-15 lg:mt-8" />
							{/if}
						</div>
					</div>
				</div>
			{/if}
		</div>
	</section>
{/if}

{#if hasAccess}
	<section class="lg:px-9" aria-labelledby="access-title">
		<div class="bg-blue text-white rounded-3xl overflow-hidden grid lg:grid-cols-2 gap-6">
			<div class="px-5 lg:pl-15 xl:pl-30 lg:pr-8 pt-6 pb-9 lg:py-12">
				<h2 id="access-title" class="text-h4">{page.accessTitle ?? 'Accès'}</h2>
				{#if page.accessContent}
					<div class="prose text-body-2 mt-6">
						<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
						{@html page.accessContent}
					</div>
				{/if}
			</div>

			{#if page.mapImage}
				{@const map = page.mapImage}
				<div class="px-5 pb-5 lg:p-8 lg:pl-0">
					<!-- A static picture rather than an embed: no Google cookies for the visitor. -->
					<div class="relative h-full min-h-75 overflow-hidden rounded-2xl bg-grey-light">
						{#if page.mapUrl}
							<a
								href={page.mapUrl}
								target="_blank"
								rel="noopener noreferrer"
								aria-label={MAP_LINK_LABEL}
								class="group absolute inset-0"
							>
								<Img
									image={map}
									alt={map.alt ?? ''}
									sizes={halfPlateSizes}
									class="absolute inset-0 w-full h-full object-cover"
								/>
								<span
									aria-hidden="true"
									class="{CTA_BASE} {ctaSizeClasses.md} absolute right-4 bottom-4 text-blue border-blue group-hover:bg-blue group-hover:text-white"
								>
									<span class={CTA_LABEL}>{MAP_LINK_LABEL}</span>
									<IconArrow class={ctaIconSizeClasses.md} />
								</span>
							</a>
						{:else}
							<Img
								image={map}
								alt={map.alt ?? ''}
								sizes={halfPlateSizes}
								class="absolute inset-0 w-full h-full object-cover"
							/>
						{/if}
					</div>
				</div>
			{:else if mapCta}
				<div class="px-5 pb-9 lg:p-8 lg:pl-0 flex items-end">
					<CtaLink cta={mapCta} inverted />
				</div>
			{/if}
		</div>
	</section>
{/if}
