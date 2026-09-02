<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="38"
	height="38"
	viewBox="0 0 38 38"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M9.5 19L19 28.5L28.5 19"
		stroke="currentColor"
		stroke-width="4"
		stroke-linecap="round"
		stroke-linejoin="round"
	/>
</svg>
