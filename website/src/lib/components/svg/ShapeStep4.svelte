<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 369, height = 371 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 369 371" fill="none" aria-hidden="true" class={className}>
	<path d="M322.918 62.0436L266.228 164.548C250.759 192.518 215.77 202.835 188.144 187.556L180.146 183.133C152.52 167.854 142.634 132.782 158.129 104.764L214.82 2.25936C140.27 -9.62336 62.5696 25.8992 23.5185 96.5093C-26.1705 186.354 5.44301 298.929 94.1595 347.994C182.876 397.059 295.034 363.997 344.723 274.152C383.774 203.542 372.561 118.846 322.869 62.0164" fill="currentColor"/>
</svg>
