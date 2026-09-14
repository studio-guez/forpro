<script lang="ts">
	import type { Snippet } from 'svelte';

	interface Props {
		class?: string;
		/** The filters, laid out at the start of the row. */
		children: Snippet;
		/** The control at the end of the row: the search, or a sort where there is none. */
		end?: Snippet;
	}

	let { class: className = '', children, end }: Props = $props();
</script>

<!-- The row a list is browsed with: filters at the start, search at the end. It
     wraps on narrow screens, the search dropping under the filters. It leaves no
     room below: what follows brings its own top padding, the same on every list. -->
<div class={['flex flex-wrap items-center justify-between gap-x-6 gap-y-4', className]}>
	<div class="flex flex-wrap items-center gap-x-6 gap-y-3">
		{@render children()}
	</div>

	{@render end?.()}
</div>
