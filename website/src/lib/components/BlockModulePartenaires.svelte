<script lang="ts">
	import type { ModulePartenairesContent, PartnerItem } from '$lib/interfaces/page';
	import CardSmall from '$lib/components/ui/CardSmall.svelte';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		content: ModulePartenairesContent;
	}

	let { content }: Props = $props();

	const onDark = $derived(content.variant === 'default');
	const background = $derived(onDark ? 'var(--color-hotpink)' : 'var(--color-white)');
	const textColor = $derived(onDark ? 'var(--color-white)' : 'var(--color-hotpink)');
	// The logos need a white plate, so on a white section they get an outline instead.
	const plateClass = $derived(
		`flex items-center justify-center rounded-xl px-6 py-4 bg-(--color-white) ${
			onDark ? '' : 'border border-(--color-grey-light)'
		}`
	);
</script>

{#snippet logo(partner: PartnerItem)}
	<Img image={partner.logo} alt={partner.label} class="h-12 md:h-16 w-auto max-w-40 object-contain" />
{/snippet}

<CardSmall title={content.title} subtitle={content.subtitle} {background} color={textColor}>
	{#if content.partners.length > 0}
		<ul class="flex flex-wrap gap-2 md:justify-end shrink-0">
			{#each content.partners as partner (partner.logo.url)}
				<li>
					{#if partner.url}
						<a href={partner.url} class="{plateClass} transition-opacity hover:opacity-70">
							{@render logo(partner)}
						</a>
					{:else}
						<div class={plateClass}>
							{@render logo(partner)}
						</div>
					{/if}
				</li>
			{/each}
		</ul>
	{/if}
</CardSmall>
