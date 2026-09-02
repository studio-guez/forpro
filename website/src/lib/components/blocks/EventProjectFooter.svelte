<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import ShareButton from '$lib/components/ui/ShareButton.svelte';
	import type { PageParent } from '$lib/interfaces/page';

	interface Props {
		parentPage: PageParent | null;
		/** Page title, used as the share sheet title. */
		title: string;
		variant: 'event' | 'project';
		class?: string;
	}

	let { parentPage, title, variant, class: className = '' }: Props = $props();

	const color = $derived(variant === 'project' ? 'var(--color-orange)' : 'var(--color-blue)');
</script>

<div class={['px-card', className]}>
	<div class="text-right">
		<ShareButton {title} {color} class="ms-auto" />
	</div>
	{#if parentPage}
	<div class="mt-12 lg:mt-18">
		<BackLink {parentPage} {color} />
	</div>
	{/if}
</div>
