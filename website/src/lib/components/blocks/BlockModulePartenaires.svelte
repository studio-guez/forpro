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
	const plateClass = $derived(
		`flex h-full items-center justify-center rounded-xl p-3 ${onDark ? 'bg-(--color-white)' : ''}`
	);
</script>

{#snippet logo(partner: PartnerItem)}
	<Img
		image={partner.logo}
		alt={partner.label}
		sizes="10rem"
		class="h-15 lg:h-25 w-auto max-w-40 object-contain"
	/>
{/snippet}

<CardSmall title={content.title} subtitle={content.subtitle} {background} color={textColor}>
	{#if content.partners.length > 0}
		<ul class="flex flex-wrap justify-end gap-4 lg:gap-6 {plateClass}">
			{#each content.partners as partner (partner.logo.url)}
				<li class="hover:scale-102 transition-transform">
					{#if partner.url}
						<a href={partner.url} target="_blank" rel="noopener noreferrer">
							{@render logo(partner)}
						</a>
					{:else}
						<div>
							{@render logo(partner)}
						</div>
					{/if}
				</li>
			{/each}
		</ul>
	{/if}
</CardSmall>
