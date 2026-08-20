{#if tags.length > 0}
	<div class="app-content-tags">
		{#if label}
			<span class="app-content-tags__label">{label}</span>
		{/if}
		{#each tags as tag (tag)}
			<span class="app-content-tags__item">{tag}</span>
		{/each}
	</div>
{/if}

<script lang="ts">
	export let tags: string[] = [];
	export let label = '';
</script>

<style lang="scss">
	.app-content-tags {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 0.5rem;
	}

	.app-content-tags__label {
		font-size: 0.75rem;
		text-transform: uppercase;
	}

	.app-content-tags__item {
		padding: 0.2em 0.9em 0.3em;
		border-radius: 4em;
		background: var(--app-color--blue);
		color: white;
		font-size: 0.75rem;
	}
</style>
