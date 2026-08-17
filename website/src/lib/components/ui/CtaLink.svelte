<script lang="ts">
	import type { PageCta } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';

	interface Props {
		cta: PageCta;
		color?: string;
		hoverColor?: string;
		size?: 'md' | 'lg';
		class?: string;
	}

	let { cta, color = 'var(--color-blue)', hoverColor = 'var(--color-white)', size = 'md', class: className = '' }: Props = $props();

	const sizeClasses = {
		md: 'text-lg gap-2.5 px-5.5 py-3.5 border-3',
		lg: 'text-2xl gap-3 px-7.5 py-4.5 border-4',
	};

	const iconSize = { md: { width: 27, height: 28 }, lg: { width: 37, height: 38 } };
</script>

<a href={cta.url} style="--color-cta: {color}; --color-cta-hover: {hoverColor}" class="font-bold leading-none inline-flex items-center rounded-full bg-transparent text-(--color-cta) border-(--color-cta) hover:bg-(--color-cta) hover:text-(--color-cta-hover) transition-colors {sizeClasses[size]} {className}">
	{cta.label}
	{#if cta.icon === 'arrow'}<IconArrow width={iconSize[size].width} height={iconSize[size].height} />
	{:else if cta.icon === 'email'}<IconEmail width={iconSize[size].width} height={iconSize[size].height} />
	{:else if cta.icon === 'plus'}<IconPlus width={iconSize[size].width} height={iconSize[size].height} />
	{/if}
</a>
