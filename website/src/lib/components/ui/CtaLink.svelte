<script lang="ts">
	import type { PageCta } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';

	interface Props {
		cta: PageCta;
		class?: string;
	}

	let { cta, class: className = '' }: Props = $props();
</script>

<a href={cta.url} class="text-2xl font-bold leading-none inline-flex items-center gap-3 px-7.5 py-4.5 rounded-full border-4 bg-transparent text-(--color-cta) border-(--color-cta) hover:bg-(--color-cta) hover:text-white transition-colors {className}">
	{cta.label}
	{#if cta.icon === 'arrow'}<IconArrow />
	{:else if cta.icon === 'email'}<IconEmail />
	{:else if cta.icon === 'plus'}<IconPlus />
	{/if}
</a>
