<script lang="ts">
	import type { PageCta } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';

	interface Props {
		cta: PageCta;
		variant?: 'cta' | 'btn';
		class?: string;
	}

	let { cta, variant = 'btn', class: className = '' }: Props = $props();
</script>

<a href={cta.url} class="{variant} inline-flex items-center gap-3 {className}">
	{cta.label}
	{#if cta.icon === 'arrow'}<IconArrow />
	{:else if cta.icon === 'email'}<IconEmail />
	{:else if cta.icon === 'plus'}<IconPlus />
	{/if}
</a>
