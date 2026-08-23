<script lang="ts">
	import type { PageParent } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';

	interface Props {
		parentPage: PageParent;
		label?: string;
		color?: string;
		class?: string;
	}

	let { parentPage, label, color = 'var(--color-blue)', class: className = '' }: Props = $props();

	const text = $derived(label ?? `Retour à ${parentPage.title}`);
</script>

<a
	href="/{parentPage.path}"
	style:color
	class="text-label group inline-flex items-center gap-2 {className}"
>
	<span class="inline-block group-hover:-translate-x-0.75 transition-transform">&larr;</span>
	{text}
</a>
