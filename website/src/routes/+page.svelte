<script lang="ts">
	import { type IPage } from '$lib/interfaces/cmsApiResponse';
	import AppPage from '$lib/components/AppPage.svelte';
	import { LottiePlayer } from '@lottiefiles/svelte-lottie-player';
	import { browser } from '$app/environment';

	export let data: IPage;
</script>

<div class="s-page-home">
	<section
		class="s-page-home__header app-flex app-flex--column app-flex--justify_center app-flex--align_center app-flex--nowrap"
	>
		{#if browser}
			<div class="s-page-home__header__logo__desktop">
				<LottiePlayer
					src="lottie/desktop_logo-header.json"
					autoplay={true}
					background="transparent"
					speed="1"
					style="width: 100%; height: 100%"
					direction="1"
					mode="normal"
					width="100%"
					height="100%"
					controls={false}
					controlsLayout="[]"
					renderer="svg"
				/>
			</div>
			<div class="s-page-home__header__logo__mobile">
				<LottiePlayer
					src="lottie/mobile_logo-header.json"
					autoplay={true}
					background="transparent"
					speed="1"
					style="width: 100%; height: 100%"
					direction="1"
					mode="normal"
					width="100%"
					height="100%"
					controls={false}
					controlsLayout="[]"
					renderer="svg"
				/>
			</div>
		{/if}
	</section>
	<AppPage {data} />
</div>

