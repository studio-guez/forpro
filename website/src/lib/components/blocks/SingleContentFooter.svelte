<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import ShareButton from '$lib/components/ui/ShareButton.svelte';
	import type { PageParent } from '$lib/interfaces/page';
	import type { Snippet } from 'svelte';

	interface Props {
		parentPage: PageParent | null;
		/** Page title, used as the share sheet title. */
		title: string;
		/** Any CSS colour: drives the share pill and the back link. */
		color?: string;
		/** Overrides the back link text, which defaults to `Retour à <parent>`. */
		backLabel?: string;
		/** Extra pills rendered next to the share button, e.g. an apply CTA. */
		actions?: Snippet;
		class?: string;
	}

	let {
		parentPage,
		title,
		color = 'var(--color-blue)',
		backLabel,
		actions,
		class: className = ''
	}: Props = $props();
</script>

<div class={['px-base', className]}>
	<div class="flex flex-wrap items-center justify-end gap-4">
		<ShareButton {title} {color} />
		{@render actions?.()}
	</div>
	{#if parentPage}
		<div class="mt-12 lg:mt-18">
			<BackLink {parentPage} label={backLabel} {color} />
		</div>
	{/if}
</div>
