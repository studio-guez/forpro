<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="18"
	height="43"
	viewBox="0 0 18 43"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M10.1602 1.5L10.1602 -6.55671e-08L7.16016 6.55671e-08L7.16016 1.5L8.66016 1.5L10.1602 1.5ZM8.66016 42.5L17.3204 27.5L-9.7092e-05 27.5L8.66016 42.5ZM8.66016 1.5L7.16016 1.5L7.16016 29L8.66016 29L10.1602 29L10.1602 1.5L8.66016 1.5Z"
		fill="currentColor"
	/>
</svg>
