<script lang="ts">
	import { contrast } from '$lib/utils/contrast.svelte';

	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<button
	type="button"
	aria-pressed={contrast.active}
	onclick={contrast.toggle}
	class="group inline-flex items-center gap-x-3 text-left {className}"
>
	<span>Contraste élevé</span>
	<span
		aria-hidden="true"
		class="shrink-0 inline-flex w-10 h-6 p-0.5 rounded-full border-2 border-current"
	>
		<span
			class="h-full aspect-square rounded-full bg-current transition-transform motion-reduce:transition-none group-aria-pressed:translate-x-4"
		></span>
	</span>
</button>
