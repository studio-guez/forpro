<script lang="ts">
	import '../style/_main.scss';
</script>

<main class="mx-auto grid justify-center">
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
</svelte:head>

<style>
	:global(body) {
		background-color: #d2c8b4;
	}
</style>
