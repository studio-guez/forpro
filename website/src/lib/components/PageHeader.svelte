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
		white: '#ffffff',
		pink: 'rgb(255, 0, 252)',
		purple: '#bea5e6'
	};

	const arrowFill = $derived(arrowColor ? (arrowColors[arrowColor] ?? 'currentColor') : 'currentColor');
</script>

<header class="flex flex-col gap-6">
	<div>
		{#if overtitle}
		<p class="text-sm uppercase tracking-[0.08em] opacity-70">{overtitle}</p>
		{/if}

		<h1 class="m-0 flex items-center gap-2 text-[clamp(2rem,5vw,4rem)] leading-[1.05]">
			<span>{title}</span>
			{#if titleHasArrow}
            <svg
                class="h-[0.9em] w-[0.9em] shrink-0"
                viewBox="0 0 24 24"
                fill="none"
                aria-hidden="true"
                style:color={arrowFill}
            >
                <path
                    d="M4 12h15m0 0-6-6m6 6-6 6"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
			{/if}
		</h1>
	</div>

	{#if cover}
    <img
        class="block h-auto w-full rounded-xl object-cover"
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
</header>
