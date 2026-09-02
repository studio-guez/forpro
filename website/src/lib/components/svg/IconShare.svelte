<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- Exported on a 28x29 artboard; the viewBox is squared to 28x28 so the icon keeps its
     aspect ratio in a square `w-* h-*` box. The cropped strip is empty, nothing clips. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="28"
	height="28"
	viewBox="0 0 28 28"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M14.2507 5.30859V16.0421M10.0755 8.61121L14.2507 5.30859L18.4258 8.61121M5.90039 13.5651V20.1704C5.90039 20.6083 6.12033 21.0283 6.51183 21.338C6.90332 21.6477 7.4343 21.8217 7.98796 21.8217H20.5134C21.067 21.8217 21.598 21.6477 21.9895 21.338C22.381 21.0283 22.601 20.6083 22.601 20.1704V13.5651"
		stroke="currentColor"
		stroke-width="3"
		stroke-linecap="round"
		stroke-linejoin="round"
	/>
</svg>
