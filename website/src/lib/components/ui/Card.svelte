<script lang="ts">
	import type { Component, Snippet } from 'svelte';

	interface Props {
		children: Snippet;
		title: string;
		hideTitle?: boolean;
		titleVariant?: 'pill' | 'plain';
		titleBackground?: string | null;
		titleColor?: string | null;
		subtitle?: string | null;
		/** Rich text rendered under the title. */
		shortDesc?: string | null;
		shapeLeft?: Component<{ class?: string }> | null;
		shapeRight?: Component<{ class?: string }> | null;
		shapeLeftClasses?: string;
		shapeRightClasses?: string;
		shapeColor?: string;
		background?: string | null;
		color?: string | null;
		class?: string;
	}

	let {
		children,
		title,
		hideTitle = false,
		titleVariant = 'pill',
		titleBackground = null,
		titleColor = null,
		subtitle = null,
		shortDesc = null,
		shapeLeft: ShapeLeft = null,
		shapeRight: ShapeRight = null,
		shapeLeftClasses = 'absolute top-0 left-0 -translate-1/6 w-2/5',
		shapeRightClasses = 'absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5',
		shapeColor = 'currentColor',
		background = null,
		color = null,
		class: className = '',
	}: Props = $props();

	// Vertical padding is only needed when the card sits on its own coloured background.
	const padded = $derived(
		!!background && background !== 'transparent' && background !== 'var(--color-white)'
	);

	const style = $derived(
		[background && `background-color: ${background}`, color && `color: ${color}`]
			.filter(Boolean)
			.join('; ')
	);

	const titleStyle = $derived(
		[titleBackground && `background-color: ${titleBackground}`, titleColor && `color: ${titleColor}`]
			.filter(Boolean)
			.join('; ')
	);

	const uid = $props.id();
	const titleId = `card-title-${uid}`;
</script>

<section
	class="px-card rounded-3xl relative overflow-hidden {className}"
	class:pt-12={padded}
	class:pb-18={padded}
	{style}
	aria-labelledby={titleId}
>
	{#if ShapeLeft}
		<div class={shapeLeftClasses} style:color={shapeColor}>
			<ShapeLeft class="w-full h-auto" />
		</div>
	{/if}
	{#if ShapeRight}
		<div class={shapeRightClasses} style:color={shapeColor}>
			<ShapeRight class="w-full h-auto" />
		</div>
	{/if}

	<div class="relative z-1">
		<div class="text-center">
			{#if hideTitle}
				<h2 id={titleId} class="sr-only">{title}</h2>
			{:else if titleVariant === 'plain'}
				<h2 id={titleId} class="text-h2 mb-12" style={titleStyle}>{title}</h2>
			{:else}
				<h2
					id={titleId}
					class="inline-block text-h3 pt-2 pb-3.5 px-8 rounded-2xl"
					style={titleStyle}
				>
					{title}
				</h2>
			{/if}
			{#if subtitle}
				<p class="text-body-1 font-bold mt-9">{subtitle}</p>
			{/if}
			{#if shortDesc}
				<div class="prose text-body-1 font-bold" class:mt-9={!hideTitle}>
					{@html shortDesc}
				</div>
			{/if}
		</div>

		{@render children()}
	</div>
</section>
