<script lang="ts">
	import type { HomePage } from '$lib/interfaces/home';
	import LottiePlayer from '$lib/components/ui/LottiePlayer.svelte';
	import HomeWelcomeCards from '$lib/components/home/HomeWelcomeCards.svelte';
	import HomeResourcesCoverflow from '$lib/components/home/HomeResourcesCoverflow.svelte';

	let { page }: { page: HomePage } = $props();

	// As wide as the hero allows, capped so the animation stays inside the hero: it is centered
	// then shifted up by 10% of its own height, which needs a 20% margin on top of that height.
	const lottieBox = 'w-full max-w-[calc(var(--hero-h)/1.2*var(--lottie-ratio))] -translate-y-1/10';
</script>

{#if page.lottie}
	<div class="lg:px-9">
		<div
			class="[--hero-h:30vh] sm:[--hero-h:50vh] md:[--hero-h:calc(100vh-6.75rem)] min-h-(--hero-h) flex items-end md:items-center justify-center rounded-3xl"
			style="background-image: linear-gradient(180deg, rgba(148, 175, 255, 0.20) 0%, rgba(166, 189, 255, 0.00) 100%);"
		>
			{#if page.lottieMobile}
				<LottiePlayer
					file={page.lottieMobile}
					alt={page.lottieMobile.alt}
					class="{lottieBox} md:hidden"
				/>
				<LottiePlayer file={page.lottie} alt={page.lottie.alt} class="{lottieBox} max-md:hidden" />
			{:else}
				<LottiePlayer file={page.lottie} alt={page.lottie.alt} class={lottieBox} />
			{/if}
		</div>
	</div>
{/if}

<section class="px-card" aria-labelledby="home-welcome-title">
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-25">
		<h1 id="home-welcome-title" class="text-h2 max-lg:text-center">{page.welcomeTitle}</h1>
		<div class="prose text-highlight text-balance text-black max-lg:text-center">
			{@html page.welcomeShortDesc}
		</div>
	</div>
</section>

<section class="px-base mt-12 lg:mt-20">
	<HomeWelcomeCards cards={page.welcomeCards} />
</section>

{#if page.resources.length > 0}
	<section class="mt-16 lg:mt-32 isolate" aria-labelledby="home-resources-title">
		<h2 id="home-resources-title" class="text-h2 text-center px-card">{page.resourcesTitle}</h2>
		<HomeResourcesCoverflow
			resources={page.resources}
			label={page.resourcesTitle}
			class="mt-9 lg:mt-12"
		/>
	</section>
{/if}
