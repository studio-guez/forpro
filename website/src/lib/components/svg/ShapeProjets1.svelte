<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 561, height = 466 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 561 466" fill="none" aria-hidden="true" class={className}>
	<path d="M-205.979 257.26L379.396 465.356L549.931 -0.675581C595.296 -124.597 483.86 -250.807 355.985 -218.344C275.14 -197.743 225.509 -121.684 304.585 83.7074C328.933 146.969 329.245 182.033 313.117 196.704C283.464 223.652 180.248 166.322 71.6214 91.8231C-50.427 8.06437 -215.447 101.708 -206.395 249.56L-205.875 257.26H-205.979Z" fill="currentColor"/>
</svg>
