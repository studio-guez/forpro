<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 25, height = 14 }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	{width}
	{height}
	viewBox="0 0 25 14"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M2.5 2L12.5 12L22.5 2"
		stroke="currentColor"
		stroke-width="4"
		stroke-linecap="round"
		stroke-linejoin="round"
	/>
</svg>
