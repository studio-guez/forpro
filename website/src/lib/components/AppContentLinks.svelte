{#if links.length > 0}
	<div class="app-content-links">
		<ul class="app-content-links__list">
			{#each links as link, index (index)}
				<li class="app-content-links__list__item">
					<a
						class="app-content-links__list__item__link"
						href={link.link}
						target="_blank"
						rel="noopener noreferrer"
					>
						{link.title || link.link}
					</a>
				</li>
			{/each}
		</ul>
	</div>
{/if}

<script lang="ts">
	import type { IExternalLink } from '$lib/interfaces/cmsApiResponse';

	export let links: IExternalLink[] = [];
</script>

<style lang="scss">
	.app-content-links__list {
		list-style: none;
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin: 0;
		padding: 0;
	}

	.app-content-links__list__item__link {
		display: inline-block;
		padding: 0.4em 1.2em;
		border-radius: 4em;
		background: var(--app-color--blue);
		color: white;
		text-decoration: none;
	}
</style>
