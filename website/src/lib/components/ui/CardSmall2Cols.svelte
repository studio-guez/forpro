<script lang="ts">
	import type { Snippet } from 'svelte';

	interface Props {
		/** Heading of the first column; also labels the whole card. */
		title: string;
		/** Heading of the second column, when it is a plain title above its content. */
		secondTitle?: string | null;
		first: Snippet;
		second: Snippet;
		class?: string;
	}

	let { title, secondTitle = null, first, second, class: className = '' }: Props = $props();

	const uid = $props.id();
	const titleId = `card-small-2cols-title-${uid}`;
</script>

<section class="md:px-9" aria-labelledby={titleId}>
	<div
		class="bg-blue text-white rounded-3xl px-5 pt-5 pb-6 md:p-8 grid md:grid-cols-2 gap-10 md:gap-16 {className}"
	>
		<div>
			<h2 id={titleId} class="text-h4">{title}</h2>
			{@render first()}
		</div>

		<div class="flex flex-col items-start gap-6">
			{#if secondTitle}
				<h2 class="text-h4">{secondTitle}</h2>
			{/if}
			{@render second()}
		</div>
	</div>
</section>
