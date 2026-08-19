<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 224, height = 221 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 224 221" fill="none" aria-hidden="true" class={className}>
	<path d="M217.227 2C239.228 141.659 168.754 213.762 121.571 112.96C90.8884 47.4089 107.599 1.87348 132.488 72.769C159.596 149.986 103.396 271.751 2 192.356" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
</svg>
