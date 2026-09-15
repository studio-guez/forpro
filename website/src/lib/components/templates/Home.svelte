<script lang="ts">
	import type { HomePage } from '$lib/interfaces/home';
	import LottiePlayer from '$lib/components/ui/LottiePlayer.svelte';
	import HomeWelcomeCards from '$lib/components/home/HomeWelcomeCards.svelte';
	import HomeResourcesCoverflow from '$lib/components/home/HomeResourcesCoverflow.svelte';

	let { page }: { page: HomePage } = $props();
</script>

{#if page.lottie}

	<div class="px-base">
		<div class="min-h-[calc(100vh-6.75rem)] flex items-center justify-center rounded-3xl" style="background-image: linear-gradient(180deg, rgba(148, 175, 255, 0.20) 0%, rgba(166, 189, 255, 0.00) 100%);">
			<LottiePlayer file={page.lottie} alt={page.lottie.alt} />
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
