<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 37, height = 38 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 37 38" fill="none" aria-hidden="true" class={className}>
	<path d="M7.70801 19.0003H29.2913M18.4997 30.0837L29.2913 19.0003L18.4997 7.91699" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
