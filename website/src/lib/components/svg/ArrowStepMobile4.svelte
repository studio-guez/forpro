<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 47, height = 115 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 47 115" fill="none" aria-hidden="true" class={className}>
	<path d="M22.5347 2.59786C23.141 2.03336 23.1749 1.08422 22.6104 0.47789C22.0459 -0.12844 21.0968 -0.162353 20.4905 0.402142L21.5126 1.5L22.5347 2.59786ZM46.7629 114.427L38.1086 99.4239L29.4424 114.42L46.7629 114.427ZM21.5126 1.5L20.4905 0.402142C-6.51476 25.5441 -11.4593 78.2822 34.2363 108.917L35.0715 107.671L35.9068 106.425C-8.05121 76.9553 -3.1731 26.5319 22.5347 2.59786L21.5126 1.5Z" fill="currentColor"/>
</svg>
