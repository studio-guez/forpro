<script lang="ts">
	import type { Component } from 'svelte';
	import type { ThreeElementsItem } from '$lib/interfaces/page';
	import ShapeInfosPratiques1 from '$lib/components/svg/ShapeInfosPratiques1.svelte';
	import ShapeInfosPratiques2 from '$lib/components/svg/ShapeInfosPratiques2.svelte';
	import ShapeInfosPratiques3 from '$lib/components/svg/ShapeInfosPratiques3.svelte';
	import ShapeInfosPratiquesDeco1 from '$lib/components/svg/ShapeInfosPratiquesDeco1.svelte';
	import ShapeInfosPratiquesDeco2 from '$lib/components/svg/ShapeInfosPratiquesDeco2.svelte';
	import ShapeInfosPratiquesDeco3 from '$lib/components/svg/ShapeInfosPratiquesDeco3.svelte';

	type Shape = Component<{ class?: string }>;

	interface Props {
		elements: ThreeElementsItem[];
		/** Fill of the organic shape behind each element. */
		blobColor: string;
		textColor: string;
		/** Fill of the background decorations; `null` hides them. */
		decoColor: string | null;
		/** One organic shape per element. */
		shapes?: Shape[];
		/** Background decorations, in the order of `decoPositions`. */
		decoShapes?: Shape[];
		/** Rotation in degrees of each element's text, cycled over the elements. */
		rotations?: number[];
		/** Absolute positioning classes, one per background decoration. */
		decoPositions?: string[];
		class?: string;
	}

	let {
		elements,
		blobColor,
		textColor,
		decoColor,
		shapes = [ShapeInfosPratiques1, ShapeInfosPratiques2, ShapeInfosPratiques3],
		decoShapes = [ShapeInfosPratiquesDeco2, ShapeInfosPratiquesDeco1, ShapeInfosPratiquesDeco3],
		rotations = [-6, 12, -6],
		decoPositions = [
			'left-[24%] top-[12%] max-xl:-rotate-30 xl:left-[44%] xl:top-[8%] w-1/4 xl:w-1/6',
			'left-[18%] bottom-[4%] max-xl:rotate-10 xl:left-[12%] xl:bottom-[-8%] w-1/3 xl:w-2/9',
			'right-[8%] max-xl:top-[42%] max-xl:-rotate-15 xl:right-[6%] xl:bottom-[-10%] w-1/5 xl:w-1/6'
		],
		class: className = ''
	}: Props = $props();
</script>

<div class="relative grid grid-cols-2 xl:grid-cols-3 xl:gap-10 items-start {className}">
	{#if decoColor}
		{#each decoShapes as DecoShape, i (i)}
			<div class="absolute {decoPositions[i]}" style:color={decoColor}>
				<DecoShape class="w-full h-auto" />
			</div>
		{/each}
	{/if}
	{#each elements as element, i (i)}
		{@const Shape = shapes[i % shapes.length]}
		<div
			class="relative aspect-15/13 w-full max-w-90 mx-auto"
			class:xl:mt-48={i === 1}
			class:max-xl:col-start-2={i === 0 || i === 2}
			class:max-xl:row-start-2={i === 1}
			class:max-xl:row-start-3={i === 2}
		>
			<div class="absolute -inset-1/8 object-contain" style:color={blobColor}>
				<Shape class="w-full h-full" />
			</div>
			<div
				class="absolute inset-0 flex flex-col items-center justify-center text-center px-[5%]"
				style:transform="rotate({rotations[i % rotations.length]}deg)"
				style:color={textColor}
			>
				{#if element.title}
					<h3 class="text-lg lg:text-3xl font-bold">{element.title}</h3>
				{/if}
				<p
					class="text-base lg:text-2xl lg:font-bold whitespace-pre-line"
					class:mt-3={element.title}
					class:lg:mt-6={element.title}
				>
					{element.description}
				</p>
			</div>
		</div>
	{/each}
</div>
