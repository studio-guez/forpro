<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 464, height = 429 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 464 429" fill="none" aria-hidden="true" class={className}>
	<path d="M-157.822 305.811L-33.6947 428.284H463.436V349.98L377.685 265.366L463.436 180.753V127.999L146.29 -184.906H92.9159L7.16406 -100.293L-78.5872 -184.906H-157.822V305.811Z" fill="currentColor"/>
</svg>
