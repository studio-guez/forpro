<script lang="ts">
	import type { ModuleCasesContent, Theme, Variant } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';

	interface Props {
		content: ModuleCasesContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	type ThemeColors = { bg: string; bgContrast: string; text: string; title: string; ctaHoverColor: string };

	const themeConfig: Record<Theme, Record<Variant, ThemeColors>> = {
		default: {
			default: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-black)',
			},
			inverted: {
				bg: 'var(--bg-grey-dark)',
				bgContrast: 'var(--color-blue)',
				text: 'var(--bg-grey-dark)',
				title: 'var(--color-blue)',
				ctaHoverColor: 'var(--color-white)',
			},
		},
		campus: {
			default: {
				bg: 'var(--color-blue)',
				bgContrast: 'var(--color-blue-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-blue)',
			},
			inverted: {
				bg: 'var(--color-white)',
				bgContrast: 'var(--color-blue)',
				text: 'var(--color-blue)',
				title: 'var(--color-blue)',
				ctaHoverColor: 'var(--color-white)',
			},
		},
		entreprendre: {
			default: {
				bg: 'var(--color-purple-light)',
				bgContrast: 'var(--color-purple-pale)',
				text: 'var(--color-white)',
				title: 'var(--color-purple-light)',
				ctaHoverColor: 'var(--color-purple-light)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-purple-pale)',
				ctaHoverColor: 'var(--color-purple-pale)',
			},
		},
		projets_jeunes: {
			default: {
				bg: 'var(--color-orange)',
				bgContrast: 'var(--color-orange-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-orange)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-orange)',
				title: 'var(--color-orange)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		tremplin_jobs: {
			default: {
				bg: 'var(--color-purple-light)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-purple-light)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-purple-light)',
				title: 'var(--color-purple-light)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		soutiens: {
			default: {
				bg: 'var(--color-pink)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-pink)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-pink)',
				text: 'var(--color-pink)',
				title: 'var(--color-pink)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		cekale: {
			default: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-purple)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-purple)',
				title: 'var(--color-purple)',
				ctaHoverColor: 'var(--color-purple-pale)',
			},
		},
		la_ref: {
			default: {
				bg: 'var(--color-pink)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-pink)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-pink)',
				text: 'var(--color-pink)',
				title: 'var(--color-pink)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		learninglab: {
			default: {
				bg: 'var(--color-teal)',
				bgContrast: 'var(--color-teal-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-teal)',
			},
			inverted: {
				bg: 'var(--color-teal-light)',
				bgContrast: 'var(--color-teal)',
				text: 'var(--color-teal)',
				title: 'var(--color-teal)',
				ctaHoverColor: 'var(--color-teal-light)',
			},
		},
		foodlab: {
			default: {
				bg: 'var(--color-orange)',
				bgContrast: 'var(--color-orange-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-orange)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-orange)',
				title: 'var(--color-orange)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		grandlab: {
			default: {
				bg: 'var(--color-red)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-red)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-red)',
				text: 'var(--color-red)',
				title: 'var(--color-red)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		makerlab: {
			default: {
				bg: 'var(--color-grey-dark)',
				bgContrast: 'var(--color-grey-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
				ctaHoverColor: 'var(--color-grey-dark)',
			},
			inverted: {
				bg: 'var(--color-grey-light)',
				bgContrast: 'var(--color-grey-dark)',
				text: 'var(--color-grey-dark)',
				title: 'var(--color-grey-dark)',
				ctaHoverColor: 'var(--color-grey-light)',
			},
		},
	};

	const colors = $derived(themeConfig[theme][content.variant]);
	const hasBackground = $derived(!!colors.bg && colors.bg !== 'transparent' && colors.bg !== 'var(--color-white)');

	const isMediaLeft = (index: number) => {
		if (content.layout === 'images-left') return true;
		if (content.layout === 'images-right') return false;
		return !(index % 2 === 0);
	};

	// For single-media rows, alternate the media column span in a 2/1/1/2 pattern.
	const singleMediaSpan = (index: number) => [2, 2 ,1, 1][index % 4];
</script>

<section
	class="px-card rounded-3xl relative overflow-hidden"
	class:pt-12={hasBackground}
	class:pb-18={hasBackground}
	style={[colors.bg && `background-color: ${colors.bg}`, colors.text && `color: ${colors.text}`].filter(Boolean).join('; ')}
	aria-label={content.hideTitle ? content.title : undefined}
	aria-labelledby={content.hideTitle ? undefined : 'module-cases-title'}
>
	<div class="mb-12 text-center relative z-1">
		{#if content.hideTitle}
			<h2 id="module-cases-title" class="sr-only">{content.title}</h2>
		{:else}
			<h2 id="module-cases-title" class="inline-block text-h3 pt-2 pb-3.5 px-8 bg-white rounded-2xl" style={colors.title && `color: ${colors.title}`}>{content.title}</h2>
		{/if}
		{#if content.intro}
			<div class="prose font-bold" class:mt-9={!content.hideTitle}>
				{@html content.intro}
			</div>
		{/if}
	</div>

	<div class="flex flex-col gap-16 relative z-1">
		{#each content.rows as row, i (i)}
			<article class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" aria-label={row.hideTitle ? row.title : undefined}>
                {#each row.media as media, m (m)}
                    <div class="overflow-hidden rounded-2xl min-h-75" class:[contain:size]={media.type !== 'video'} class:lg:col-span-2={singleMediaSpan(m) === 2}>
                        {#if media.type === 'video'}
                            <VideoPlayer src={media.url} />
                        {:else}
                            <img
                                src={media.url}
                                srcset={media.srcset}
                                width={media.width}
                                height={media.height}
                                alt={media.alt ?? ''}
                                style:object-position={media.focus ?? 'center'}
                                class="w-full h-full object-cover"
                            />
                        {/if}
                    </div>
                {/each}
				<div class="min-h-75 flex flex-col justify-center" class:lg:order-first={!isMediaLeft(i)}>
					{#if row.hideTitle}
						<h3 class="sr-only">{row.title}</h3>
					{:else}
						<h3 class="text-h4 mb-6">{row.title}</h3>
					{/if}
					<div class="prose text-body-2">
						{@html row.description}
					</div>
					{#if row.cta}
						<CtaLink cta={row.cta} color={colors.text} hoverColor={colors.ctaHoverColor} class="mt-8" />
					{/if}
				</div>
			</article>
		{/each}
	</div>
	{#if theme === 'learninglab'}
	<svg xmlns="http://www.w3.org/2000/svg" width="459" height="567" viewBox="0 0 459 567" fill="none" aria-hidden="true" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
		<path d="M126.147 515.734L48.9662 413.919L314.239 212.829L391.42 314.644L458.044 264.139L8.39258 -329.031L-58.2319 -278.527L15.9158 -180.713L-249.357 20.3772L-323.505 -77.4367L-390.048 -26.994L59.6038 566.176L126.147 515.734Z" fill="{colors.bgContrast}"/>
	</svg>
	<svg xmlns="http://www.w3.org/2000/svg" width="532" height="342" viewBox="0 0 532 342" fill="none" aria-hidden="true" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
		<path d="M805.557 184.936L604.687 -263.807C591.29 -293.735 572.337 -299.132 542.662 -285.721L364.517 -205.212C271.346 -163.105 262.629 -67.4485 239.239 11.1157C208.542 114.095 149.412 171.012 46.1607 217.674L-0.000913166 238.536L30.3852 306.418L76.5469 285.557C179.798 238.895 261.412 232.205 358.459 277.453C432.573 311.952 509.491 368.909 602.661 326.802L780.807 246.293C810.482 232.882 819.038 215.053 805.641 185.125" fill="{colors.bgContrast}"/>
	</svg>
	{:else if theme === 'foodlab'}
	<svg xmlns="http://www.w3.org/2000/svg" width="450" height="700" viewBox="0 0 450 700" fill="none" aria-hidden="true" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
		<path d="M-406.704 367.08L-177.128 193.05C-127.901 155.733 -79.8799 86.8382 -42.341 52.5537C-4.70967 18.199 15.5104 3.16255 52.0058 -12.7005C88.4087 -28.4934 136.936 -7.72541 137.731 34.0726C138.526 75.8706 103.039 107.288 118.224 124.628C130.815 139.124 151.458 120.416 182.642 96.7776C227.058 63.1081 340.475 -5.82019 414.338 91.6177C492.62 194.885 418.897 283.117 400.701 303.322C382.575 323.62 314.876 390.966 238.465 439.709C162.055 488.453 87.7525 513.451 39.0366 546.446C-9.67925 579.441 -162.594 699.291 -162.594 699.291L-406.797 367.15L-406.704 367.08Z" fill={colors.bgContrast}/>
	</svg>
	<svg xmlns="http://www.w3.org/2000/svg" width="613" height="524" viewBox="0 0 613 524" fill="none" aria-hidden="true" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
  		<path d="M804.987 -157.715L0 167.721C66.2571 331.612 221.24 432.923 387.271 438.862L421.316 523.075L749.002 390.6L714.957 306.387C830.117 186.77 871.244 6.17629 804.987 -157.715Z" fill="{colors.bgContrast}"/>
	</svg>
	{:else if theme === 'grandlab'}
	<svg xmlns="http://www.w3.org/2000/svg" width="389" height="750" viewBox="0 0 389 750" fill="none" aria-hidden="true" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
		<path d="M341.363 539.976C343.347 487.62 353.294 430.394 375.391 369.203C402.753 293.238 386.037 208.14 330.442 149.601C290.117 107.169 247.839 50.6286 221.895 -18.0027C186.533 -111.551 91.7147 -169.936 -7.40456 -156.724C-9.51641 -156.434 -11.5358 -156.215 -13.6476 -155.925C-120.856 -144.158 -202.637 -55.7919 -205.288 52.0014C-207.104 123.608 -220.117 198.896 -253.739 267.949C-297.496 357.653 -272.718 466.072 -194.185 527.768C-152.912 560.302 -113.368 599.973 -85.6328 646.556C-42.646 719.025 39.7898 758.383 123.411 748.177C129.306 747.497 135.272 746.909 141.378 746.506C251.098 739.974 337.259 649.89 341.525 539.998" fill="{colors.bgContrast}"/>
	</svg>
	<svg xmlns="http://www.w3.org/2000/svg" width="560" height="575" viewBox="0 0 560 575" fill="none" aria-hidden="true" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
		<path d="M191.424 574.292L217.155 539.075C264.67 568.972 327.681 557.519 361.277 511.537L755.283 -27.7333C788.939 -73.7991 780.926 -137.62 738.227 -174.11L764.018 -209.41L584.792 -341.555L559.001 -306.254C511.485 -336.152 448.474 -324.698 414.878 -278.716L20.873 260.554C-12.7839 306.62 -4.76984 370.441 37.929 406.931L12.1985 442.148L191.424 574.292Z" fill="{colors.bgContrast}"/>
	</svg>

	{:else if theme === 'makerlab'}
	<svg xmlns="http://www.w3.org/2000/svg" width="376" height="521" viewBox="0 0 376 521" fill="none" aria-hidden="true" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
		<path d="M267.083 375.452V87.719C332.594 73.5857 375.794 49.4079 375.794 21.8523C375.794 -21.7032 267.438 -57.0811 133.749 -57.0811C0.060454 -57.0811 -108.295 -21.7032 -108.295 21.8523C-108.295 49.4079 -65.0951 73.5857 0.416009 87.719V375.452C-65.0951 389.586 -108.295 413.852 -108.295 441.319C-108.295 484.875 0.060454 520.252 133.749 520.252C267.438 520.252 375.794 484.875 375.794 441.319C375.794 413.763 332.594 389.586 267.083 375.452Z" fill="{colors.bgContrast}"/>
	</svg>
	<svg xmlns="http://www.w3.org/2000/svg" width="428" height="437" viewBox="0 0 428 437" fill="none" aria-hidden="true" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
  		<path d="M537.966 -94.9621L230.922 -184.324L-0.000223469 36.9407L76.1468 347.479L383.191 436.841L614.088 215.664L537.966 -94.9621Z" fill="{colors.bgContrast}"/>
	</svg>
	{:else}
    <svg xmlns="http://www.w3.org/2000/svg" width="464" height="429" viewBox="0 0 464 429" fill="none" aria-hidden="true" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
        <path d="M-157.822 305.811L-33.6947 428.284H463.436V349.98L377.685 265.366L463.436 180.753V127.999L146.29 -184.906H92.9159L7.16406 -100.293L-78.5872 -184.906H-157.822V305.811Z" fill={colors.bgContrast}/>
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" width="413" height="429" viewBox="0 0 413 429" fill="none" aria-hidden="true" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
        <path d="M539.099 274.646C513.446 318.814 405.248 249.406 360.873 274.853C316.601 300.299 322.289 428.771 271.19 428.667C220.091 428.667 226.091 300.195 181.922 274.646C137.65 249.096 29.245 318.194 3.69538 273.922C-21.7508 229.65 92.5504 170.689 92.6538 119.59C92.6538 68.4904 -21.4406 9.21965 4.21245 -34.9491C29.8655 -79.1178 138.064 -9.70984 182.439 -35.156C226.815 -60.6021 221.022 -189.074 272.121 -188.971C323.221 -188.971 317.221 -60.4986 361.39 -34.9491C405.662 -9.39948 514.066 -78.4972 539.616 -34.2251C565.166 10.0471 450.762 69.0077 450.658 120.107C450.658 171.206 564.752 230.477 539.099 274.646Z" fill={colors.bgContrast}/>
    </svg>
	{/if}
</section>
