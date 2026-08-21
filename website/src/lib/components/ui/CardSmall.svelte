<script lang="ts">
	import type { Snippet } from 'svelte';
	import type { CmsImage } from '$lib/interfaces/page';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		children: Snippet;
		title: string;
		subtitle?: string | null;
		background?: string | null;
		color?: string | null;
		backgroundImage?: CmsImage | null;
		class?: string;
	}

	let {
		children,
		title,
		subtitle = null,
		background = null,
		color = null,
		backgroundImage = null,
		class: className = '',
	}: Props = $props();

	const style = $derived(
		[background && `background-color: ${background}`, color && `color: ${color}`]
			.filter(Boolean)
			.join('; ')
	);

	const uid = $props.id();
	const titleId = `card-small-title-${uid}`;
</script>

<section
	class="px-card py-12 md:py-16 rounded-3xl relative overflow-hidden {className}"
	{style}
	aria-labelledby={titleId}
>
	{#if backgroundImage}
		<Img image={backgroundImage} alt="" class="absolute inset-0 w-full h-full object-cover" />
		<div class="absolute inset-0 bg-black/30"></div>
	{/if}

	<div
		class="relative z-1"
	>
		<div class="md:max-w-2xl">
			<h2 id={titleId} class="text-h2">{title}</h2>
			{#if subtitle}
				<p class="text-body-1 mt-4">{subtitle}</p>
			{/if}
		</div>
        <div class="flex justify-end">
		{@render children()}
        </div>
	</div>
</section>
