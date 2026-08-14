<script lang="ts">
	import type { ArrowColor, CmsImage } from '$lib/interfaces/page';

	interface Props {
		title: string;
		overtitle?: string | null;
		titleHasArrow?: boolean;
		arrowColor?: ArrowColor | '' | null;
		cover?: CmsImage | null;
	}

	let { title, overtitle = null, titleHasArrow = false, arrowColor = null, cover = null }: Props =
		$props();

	const arrowColors: Record<ArrowColor, string> = {
		white: 'fill-white',
		pink: 'fill-pink',
		purple: 'fill-purple',
		orange: 'fill-orange',
		blue: 'fill-blue',
	};

	const arrowColorClass = $derived(arrowColor ? (arrowColors[arrowColor] ?? 'fill-white') : 'fill-white');
</script>

<div class="min-h-138 relative text-white bg-grey-light rounded-3xl overflow-hidden flex">
	<div class="w-full bg-linear-to-t from-blacker/20 relative z-1 flex flex-col justify-end py-6 px-8">
		{#if overtitle || titleHasArrow}
		<div>
			{#if overtitle}
			<p class="inline-block text-h4 -mb-2">{overtitle}</p>
			{/if}
			{#if titleHasArrow}
			<svg xmlns="http://www.w3.org/2000/svg" width="133" height="69" viewBox="0 0 133 69" fill="none" class="inline-block absolute -translate-y-1/2 ml-1 w-32.5 h-16" aria-hidden="true" focusable="false">
				<path
					d="M3.33409 40.7394L2.81418 42.1464L0.000150327 41.1065L0.520069 39.6995L1.92708 40.2194L3.33409 40.7394ZM57.8128 36.4215L56.9022 37.6134V37.6134L57.8128 36.4215ZM46.6197 43.9079L45.6672 45.0667L46.6197 43.9079ZM132.057 68.4542L116.861 60.1413L131.658 51.1383L132.057 68.4542ZM1.92708 40.2194L0.520069 39.6995C3.82474 30.7564 11.2552 24.0863 21.5268 22.5006C31.7608 20.9206 44.5722 24.417 58.7235 35.2296L57.8128 36.4215L56.9022 37.6134C43.1622 27.1151 31.1572 24.0493 21.9845 25.4654C12.8494 26.8757 6.2826 32.7601 3.33409 40.7394L1.92708 40.2194ZM57.8128 36.4215L58.7235 35.2296C62.8493 38.3819 65.4202 41.4278 66.561 44.1041C67.1349 45.4504 67.3785 46.7801 67.1902 48.0084C66.997 49.2696 66.3588 50.3342 65.3513 51.0819C63.4155 52.5184 60.4402 52.5905 57.1941 51.6464C53.8679 50.679 49.9165 48.5598 45.6672 45.0667L46.6197 43.9079L47.5722 42.7492C51.5965 46.0573 55.1931 47.9401 58.0319 48.7658C60.9508 49.6147 62.7574 49.2709 63.5635 48.6728C63.9268 48.4032 64.1495 48.0457 64.2249 47.5539C64.3053 47.0293 64.2253 46.2751 63.8013 45.2805C62.9463 43.2747 60.8241 40.61 56.9022 37.6134L57.8128 36.4215ZM46.6197 43.9079L45.6672 45.0667C36.05 37.1611 32.1105 27.6356 32.9211 19.2548C33.7336 10.8551 39.3081 3.86267 48.1249 1.17974C56.9101 -1.49359 68.7148 0.1516 82.1302 8.45146C95.5521 16.7554 110.692 31.7741 126.294 56.0985L125.032 56.9084L123.769 57.7183C108.323 33.6383 93.4778 18.9997 80.5518 11.0027C67.6193 3.00159 56.7131 1.70217 48.9982 4.0498C41.3148 6.38784 36.5999 12.3819 35.9072 19.5436C35.2127 26.7241 38.5523 35.3346 47.5722 42.7492L46.6197 43.9079Z" class={arrowColorClass}
				/>
			</svg>
			{/if}
		</div>
		{/if}
		<h1 class="text-h0">
			{title}
		</h1>
	</div>

	{#if cover}
    <img
        class="absolute inset-0 h-full w-full object-cover"
        src={cover.url}
        srcset={cover.srcset}
        sizes="100vw"
        width={cover.width}
        height={cover.height}
        alt={cover.alt ?? ''}
        style:object-position={cover.focus ?? 'center'}
        loading="eager"
        fetchpriority="high"
    />
	{/if}
</div>
