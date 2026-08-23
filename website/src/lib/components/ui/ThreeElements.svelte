<script lang="ts">
	import type { ThreeElementsItem } from '$lib/interfaces/page';
	import ShapeInfosPratiques1 from '$lib/components/svg/ShapeInfosPratiques1.svelte';
	import ShapeInfosPratiques2 from '$lib/components/svg/ShapeInfosPratiques2.svelte';
	import ShapeInfosPratiques3 from '$lib/components/svg/ShapeInfosPratiques3.svelte';
	import ShapeInfosPratiquesDeco1 from '$lib/components/svg/ShapeInfosPratiquesDeco1.svelte';
	import ShapeInfosPratiquesDeco2 from '$lib/components/svg/ShapeInfosPratiquesDeco2.svelte';
	import ShapeInfosPratiquesDeco3 from '$lib/components/svg/ShapeInfosPratiquesDeco3.svelte';

	interface Props {
		elements: ThreeElementsItem[];
		/** Fill of the organic shape behind each element. */
		blobColor: string;
		textColor: string;
		decoColor: string;
		class?: string;
	}

	let { elements, blobColor, textColor, decoColor, class: className = '' }: Props = $props();

	// One organic shape per element.
	const shapes = [ShapeInfosPratiques1, ShapeInfosPratiques2, ShapeInfosPratiques3];
	const rotations = [-8, -6, -8];
</script>

<div class="relative grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-6 items-start {className}">
	<div class="hidden md:block absolute left-[36%] top-[8%] w-32 lg:w-40" style:color={decoColor}>
		<ShapeInfosPratiquesDeco2 class="w-full h-auto" />
	</div>
	<div class="hidden md:block absolute left-[4%] bottom-[-6%] w-44 lg:w-56" style:color={decoColor}>
		<ShapeInfosPratiquesDeco1 class="w-full h-auto" />
	</div>
	<div class="hidden md:block absolute right-[4%] bottom-[-4%] w-28 lg:w-36" style:color={decoColor}>
		<ShapeInfosPratiquesDeco3 class="w-full h-auto" />
	</div>
	{#each elements as element, i (i)}
		{@const Shape = shapes[i % 3]}
		<div class="relative aspect-15/13 w-full max-w-90 mx-auto" class:md:mt-24={i === 1}>
			<div class="absolute inset-0" style:color={blobColor}>
				<Shape class="w-full h-full" />
			</div>
			<div
				class="absolute inset-0 flex flex-col items-center justify-center text-center px-[16%]"
				style:transform="rotate({rotations[i % 3]}deg)"
				style:color={textColor}
			>
				<h3 class="text-body-2 font-bold">{element.title}</h3>
				<div class="prose text-label font-bold mt-3">
					{@html element.description}
				</div>
			</div>
		</div>
	{/each}
</div>
