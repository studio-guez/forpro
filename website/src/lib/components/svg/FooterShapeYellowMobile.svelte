<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- Fixed size, never stretched. The box is only 110px tall on purpose: the path runs on far
	 below it and the SVG clips it, so the parent anchors the element at the bottom-left of the
	 content column and the footer's bottom edge is the shape's bottom edge. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="402"
	height="110"
	viewBox="0 0 402 110"
	aria-hidden="true"
	class={className}
>
	<path
		d="M419.584 417.071L372.937 28.3359C370.834 10.8113 354.687 -1.87545 337.162 0.227512L-47.1017 46.3372C-64.7134 48.4506 -77.4009 64.5973 -75.298 82.1219L-67.9432 143.415C-65.8507 160.853 -78.3966 176.72 -95.7493 178.802L-276.356 200.474C-293.967 202.588 -306.569 218.724 -304.466 236.249L-272.809 500.067C-270.696 517.678 -254.559 530.278 -237.033 528.175L391.463 452.759C409.075 450.645 421.676 434.509 419.573 416.984"
		class="fill-yellow"
	/>
</svg>
