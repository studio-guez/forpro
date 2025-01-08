<script lang="ts">
	import '../style/_main.scss';
	import {afterNavigate} from "$app/navigation";

	declare var _paq: unknown

	afterNavigate((navigation) => {
		if (_paq) {
			_paq.push(['setCustomUrl', '/' + window.location.href])
			_paq.push(['setDocumentTitle', window.location.pathname])
			_paq.push(['setReferrerUrl', navigation.from])
			_paq.push(['trackPageView'])
		}
	})


</script>

<main class="mx-auto grid justify-center overflow-hidden lg:overflow-auto">
	<div class="max-w-[1920px]">
		<slot />
	</div>
</main>

<svelte:head>
	<title>Foodlab - Imaginé par ForPro</title>
	<meta
		name="description"
		content="Le FoodLab est un des 6 labs dédiés à la formation professionnelle au sein du campus ForPro."
	/>

	<!-- Matomo -->
	<script>
		var _paq = window._paq = window._paq || [];
		/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
			var u="//matomo.for-pro.ch/";
			_paq.push(['setTrackerUrl', u+'matomo.php']);
			_paq.push(['setSiteId', '2']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
		})();
	</script>
	<!-- End Matomo Code -->
</svelte:head>

<style>
	:global(body) {
		background-color: #d2c8b4;
	}
</style>
