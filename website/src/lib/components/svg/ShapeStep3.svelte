<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 454, height = 364 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 454 364" fill="none" aria-hidden="true" class={className}>
	<path d="M441.924 213.679L376.375 232.096L328.391 61.3102L393.94 42.8936L381.889 0.000119507L0 107.295L12.0513 150.188L75.0249 132.496L123.008 303.281L60.0349 320.974L72.0714 363.815L453.96 256.52L441.924 213.679Z" fill="currentColor"/>
</svg>
