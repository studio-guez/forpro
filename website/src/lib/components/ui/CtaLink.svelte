<script lang="ts">
	import type { PageCta } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPhone from '$lib/components/svg/IconPhone.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';

	interface Props {
		cta: PageCta;
		color?: string;
		inverted?: boolean;
		size?: 'md' | 'lg';
		class?: string;
	}

	let { cta, color = 'var(--color-blue)', inverted = false, size = 'md', class: className = '' }: Props = $props();

	const sizeClasses = {
		md: 'text-base lg:text-lg gap-2 lg:gap-2.5 px-2.25 lg:px-5.5 h-10.5 lg:h-12.5 border-3',
		lg: 'text-base lg:text-2xl gap-2 lg:gap-3 px-2.25 lg:px-7.5 h-10.5 lg:h-17 border-3 lg:border-4',
	};

	const colorClasses = $derived(
		inverted
			? 'text-white border-white hover:bg-white hover:text-(--color-cta)'
			: 'text-(--color-cta) border-(--color-cta) hover:bg-(--color-cta) hover:text-white'
	);

	const iconSize = { md: { width: 27, height: 28 }, lg: { width: 37, height: 38 } };
</script>

<a href={cta.url} target={cta.target ?? undefined} rel={cta.target === '_blank' ? 'noopener noreferrer' : undefined} style:--color-cta={color} class="font-bold leading-none inline-flex items-center rounded-full bg-transparent {colorClasses} transition-colors {sizeClasses[size]} {className}">
	<span class="text-trim">{cta.label}</span>
	{#if cta.icon === 'arrow'}<IconArrow width={iconSize[size].width} height={iconSize[size].height} />
	{:else if cta.icon === 'email'}<IconEmail width={iconSize[size].width} height={iconSize[size].height} />
	{:else if cta.icon === 'phone'}<IconPhone width={iconSize[size].width} height={iconSize[size].height} />
	{:else if cta.icon === 'plus'}<IconPlus width={iconSize[size].width} height={iconSize[size].height} />
	{/if}
</a>
