{#if blocks.length > 0}
	<div class="app-content-blocks">
		{#each blocks as block, index (index)}
			<section class="app-content-blocks__item">
				<h2 class="app-content-blocks__item__title fp-heading-h4">{block.title}</h2>
				{#if block.description}
					<div class="app-content-blocks__item__description">{@html block.description}</div>
				{/if}
			</section>
		{/each}
	</div>
{/if}

<script lang="ts">
	import type { IContentBlock } from '$lib/interfaces/cmsApiResponse';

	export let blocks: IContentBlock[] = [];
</script>

<style lang="scss">
	.app-content-blocks {
		display: flex;
		flex-direction: column;
		gap: 2rem;
		width: 100%;
	}

	.app-content-blocks__item__title {
		margin: 0 0 0.5rem;
	}
</style>
