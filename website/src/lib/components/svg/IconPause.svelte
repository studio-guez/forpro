<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="32"
	height="32"
	viewBox="0 0 32 32"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M12.5 9.5V22.5M19.5 9.5V22.5"
		stroke="currentColor"
		stroke-width="3"
		stroke-linecap="round"
		stroke-linejoin="round"
	/>
</svg>
