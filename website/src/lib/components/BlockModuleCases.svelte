<script lang="ts">
	import type { ModuleCasesContent, Theme, Variant } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import IconPlay from '$lib/components/svg/IconPlay.svelte';

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
				bg: 'var(--color-white)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-black)',
				title: 'var(--color-purple-light)',
				ctaHoverColor: 'var(--color-white)',
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

	const playVideo = (event: MouseEvent) => {
		const button = event.currentTarget as HTMLElement;
		const video = button.previousElementSibling as HTMLVideoElement | null;
		if (!video) return;
		video.controls = true;
		video.play();
		button.hidden = true;
	};
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
                    <div class="overflow-hidden rounded-2xl min-h-75 [contain:size]" class:lg:col-span-2={singleMediaSpan(m) === 2}>
                        {#if media.type === 'video'}
                            <div class="relative w-full h-full">
                                <!-- svelte-ignore a11y_media_has_caption -->
                                <video
                                    src={media.url}
                                    playsinline
                                    controlslist="nodownload"
                                    class="w-full h-full object-cover"
                                ></video>
                                <button
                                    type="button"
                                    onclick={playVideo}
                                    aria-label="Lire la vidéo"
                                    class="absolute inset-0 grid place-items-center cursor-pointer text-white opacity-80"
                                >
                                    <IconPlay class="w-24 h-auto" />
                                </button>
                            </div>
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
    <svg xmlns="http://www.w3.org/2000/svg" width="464" height="429" viewBox="0 0 464 429" fill="none" class="absolute top-0 left-0 -translate-1/6 w-2/5 h-auto">
        <path d="M-157.822 305.811L-33.6947 428.284H463.436V349.98L377.685 265.366L463.436 180.753V127.999L146.29 -184.906H92.9159L7.16406 -100.293L-78.5872 -184.906H-157.822V305.811Z" fill={colors.bgContrast}/>
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" width="413" height="429" viewBox="0 0 413 429" fill="none" class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5 h-auto">
        <path d="M539.099 274.646C513.446 318.814 405.248 249.406 360.873 274.853C316.601 300.299 322.289 428.771 271.19 428.667C220.091 428.667 226.091 300.195 181.922 274.646C137.65 249.096 29.245 318.194 3.69538 273.922C-21.7508 229.65 92.5504 170.689 92.6538 119.59C92.6538 68.4904 -21.4406 9.21965 4.21245 -34.9491C29.8655 -79.1178 138.064 -9.70984 182.439 -35.156C226.815 -60.6021 221.022 -189.074 272.121 -188.971C323.221 -188.971 317.221 -60.4986 361.39 -34.9491C405.662 -9.39948 514.066 -78.4972 539.616 -34.2251C565.166 10.0471 450.762 69.0077 450.658 120.107C450.658 171.206 564.752 230.477 539.099 274.646Z" fill={colors.bgContrast}/>
    </svg>
</section>
