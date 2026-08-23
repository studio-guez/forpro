<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 376, height = 346 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 376 346" fill="none" aria-hidden="true" class={className}>
	<path d="M264.839 0.000647451L76.9115 19.6903L0.000179398 192.292L111.011 345.149L298.938 325.46L375.855 152.912L264.839 0.000647451Z" fill="currentColor"/>
</svg>
