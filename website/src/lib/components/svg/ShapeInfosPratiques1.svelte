<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 449, height = 423 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 449 423" fill="none" aria-hidden="true" class={className}>
	<path d="M45.4679 147.874C55.3341 174.988 57.9345 230.361 4.05775e-05 331.852C4.05775e-05 331.852 254.376 550.847 448.05 309.42C448.05 309.42 419.461 49.1282 235.412 42.7261C235.412 42.7261 232.661 39.1722 227.536 33.8437C168.085 -28.3663 63.27 -0.620716 42.0284 82.8274C36.5673 104.328 37.8265 127.055 45.4037 147.878" fill="currentColor"/>
</svg>
