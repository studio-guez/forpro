<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 247, height = 136 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 247 136" fill="none" aria-hidden="true" class={className}>
	<path d="M2.00033 92.5448C36.0595 11.7068 176.055 -6.41078 244.497 5.17733M3.66531 133.646C23.9059 89.7451 72.5997 62.8814 119.394 60.0285" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
</svg>
