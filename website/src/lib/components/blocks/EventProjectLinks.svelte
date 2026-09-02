<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import type { ContentExternalLink } from '$lib/interfaces/eventProject';

	interface Props {
		links: ContentExternalLink[];
		title?: string;
		class?: string;
	}

	let { links, title = 'Liens externes', class: className = '' }: Props = $props();
</script>

{#if links.length > 0}
	<section class={['px-card', className]} aria-label={title}>
		<div class="grid grid-cols-1 lg:grid-cols-4 gap-y-3 gap-x-6">
			<h2 class="text-body-3 font-bold">{title}</h2>
			<ul>
				{#each links as link, index (index)}
					<li>
						<a
							href={link.url}
							target="_blank"
							rel="noopener noreferrer"
							class="text-body-2 inline-flex items-center gap-2 underline decoration-transparent transition-colors hover:decoration-current"
						>
							<IconLink class="shrink-0 w-5 h-5 text-blue" />
							{link.title || link.url}
						</a>
					</li>
				{/each}
			</ul>
		</div>
	</section>
{/if}
