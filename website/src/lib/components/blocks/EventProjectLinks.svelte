<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import type { ContentExternalLink } from '$lib/interfaces/eventProject';

	interface Props {
		links: ContentExternalLink[];
		/** Section heading; null or empty falls back to "Liens externes". */
		title?: string | null;
		class?: string;
	}

	let { links, title: customTitle = null, class: className = '' }: Props = $props();

	const title = $derived(customTitle || 'Liens externes');
</script>

{#if links.length > 0}
	<section class={['px-base', className]} aria-label={title}>
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-y-3 gap-x-6">
			<h2 class="text-body-2 font-bold">{title}</h2>
			<ul class="text-body-2 lg:col-span-2">
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
