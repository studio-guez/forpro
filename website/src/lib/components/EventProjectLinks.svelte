<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
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
		<h2 class="text-h4 text-teal">{title}</h2>
		<ul class="mt-6 space-y-3">
			{#each links as link, index (index)}
				<li>
					<a
						href={link.url}
						target="_blank"
						rel="noopener noreferrer"
						class="text-body-2 inline-flex items-center gap-2 text-blue underline-offset-4 hover:underline"
					>
						{link.title || link.url}
						<IconArrow width={20} height={20} class="shrink-0" />
					</a>
				</li>
			{/each}
		</ul>
	</section>
{/if}
