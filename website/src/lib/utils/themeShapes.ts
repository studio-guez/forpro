import type { Component } from 'svelte';
import type { Theme } from '$lib/interfaces/page';
import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';
import ShapeCasesLearninglab1 from '$lib/components/svg/ShapeCasesLearninglab1.svelte';
import ShapeCasesLearninglab2 from '$lib/components/svg/ShapeCasesLearninglab2.svelte';
import ShapeCasesFoodlab1 from '$lib/components/svg/ShapeCasesFoodlab1.svelte';
import ShapeCasesFoodlab2 from '$lib/components/svg/ShapeCasesFoodlab2.svelte';
import ShapeCasesGrandlab1 from '$lib/components/svg/ShapeCasesGrandlab1.svelte';
import ShapeCasesGrandlab2 from '$lib/components/svg/ShapeCasesGrandlab2.svelte';
import ShapeCasesMakerlab1 from '$lib/components/svg/ShapeCasesMakerlab1.svelte';
import ShapeCasesMakerlab2 from '$lib/components/svg/ShapeCasesMakerlab2.svelte';
import ShapeInfosPratiques1 from '$lib/components/svg/ShapeInfosPratiques1.svelte';
import ShapeInfosPratiques2 from '$lib/components/svg/ShapeInfosPratiques2.svelte';
import ShapeInfosPratiques3 from '$lib/components/svg/ShapeInfosPratiques3.svelte';
import ShapeInfosPratiquesFoodlab1 from '$lib/components/svg/ShapeInfosPratiquesFoodlab1.svelte';
import ShapeInfosPratiquesFoodlab2 from '$lib/components/svg/ShapeInfosPratiquesFoodlab2.svelte';
import ShapeInfosPratiquesFoodlab3 from '$lib/components/svg/ShapeInfosPratiquesFoodlab3.svelte';

/**
 * Decorative background shapes (left, right) per theme. Each shape carries its own
 * positioning classes: the SVGs range from 0.52 to 1.56 in aspect ratio, so a single
 * width would make the tall ones overflow and the wide ones look undersized.
 */
export type ShapeSpec = [component: Component<{ class?: string }>, classes: string];

const defaultShapes: [ShapeSpec, ShapeSpec] = [
	[ShapeCasesDefault1, 'absolute top-0 left-0 -translate-1/6 w-2/5'],
	[ShapeCasesDefault2, 'absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5']
];

const shapePairs: Partial<Record<Theme, [ShapeSpec, ShapeSpec]>> = {
	learninglab: [
		[ShapeCasesLearninglab1, 'absolute top-0 left-0 -translate-x-1/6 -translate-y-1/8 w-1/3'],
		[ShapeCasesLearninglab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/4 w-1/2']
	],
	foodlab: [
		[ShapeCasesFoodlab1, 'absolute top-0 left-0 -translate-x-1/5 -translate-y-1/12 w-1/3'],
		[ShapeCasesFoodlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/5 w-2/5']
	],
	grandlab: [
		[ShapeCasesGrandlab1, 'absolute top-0 left-0 -translate-x-1/5 -translate-y-1/12 w-1/4'],
		[ShapeCasesGrandlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/6 w-2/5']
	],
	makerlab: [
		[ShapeCasesMakerlab1, 'absolute top-0 left-0 -translate-x-1/6 -translate-y-1/10 w-1/3'],
		[ShapeCasesMakerlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/6 w-2/5']
	]
};

/** Resolve the shape pair a card should draw, falling back to the default pair. */
export function getThemeShapes(theme: Theme): [ShapeSpec, ShapeSpec] {
	return shapePairs[theme] ?? defaultShapes;
}

/**
 * The three organic blobs drawn behind the elements of a `3elements` block, with the
 * rotation of the text sitting on each one — the tilt only reads well against the shape
 * it is paired with, so both travel together.
 */
export interface ElementBlobs {
	shapes: [Component<{ class?: string }>, Component<{ class?: string }>, Component<{ class?: string }>];
	rotations: [number, number, number];
}

const defaultElementBlobs: ElementBlobs = {
	shapes: [ShapeInfosPratiques1, ShapeInfosPratiques2, ShapeInfosPratiques3],
	rotations: [-6, 12, -6]
};

const elementBlobs: Partial<Record<Theme, ElementBlobs>> = {
	foodlab: {
		shapes: [
			ShapeInfosPratiquesFoodlab1,
			ShapeInfosPratiquesFoodlab2,
			ShapeInfosPratiquesFoodlab3
		],
		rotations: [10, -10, -10]
	}
};

/** Resolve the element blobs a `3elements` block should draw, falling back to the default trio. */
export function getThemeElementBlobs(theme: Theme): ElementBlobs {
	return elementBlobs[theme] ?? defaultElementBlobs;
}
