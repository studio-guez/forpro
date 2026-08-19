<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 428, height = 437 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 428 437" fill="none" aria-hidden="true" class={className}>
	<path d="M537.966 -94.9621L230.922 -184.324L-0.000223469 36.9407L76.1468 347.479L383.191 436.841L614.088 215.664L537.966 -94.9621Z" fill="currentColor"/>
</svg>
