<script lang="ts">
	import type { PageParent } from '$lib/interfaces/page';

	interface Props {
		title: string;
		text: string;
		parentPage?: PageParent | null;
	}

	let { title, text, parentPage = null }: Props = $props();
</script>

<section class="py-18 px-base relative" aria-labelledby="page-intro-title">
	{#if parentPage}
		<div class="mb-6 mt-[calc(-1.5rem-1lh)]">
			<a href="/{parentPage.slug}" class="text-label text-blue hover:opacity-50 transition:opacity">&larr; {parentPage.title}</a>
		</div>
	{/if}
	<h2 id="page-intro-title" class="text-h2 mb-12 text-center">{title}</h2>
	<div class="prose text-center">
		{@html text}
	</div>
</section>
