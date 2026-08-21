<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 443, height = 536 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 443 536" fill="none" aria-hidden="true" class={className}>
	<path d="M403.939 -293.67H-61.8494C-82.9511 -293.67 -100.122 -276.396 -100.122 -255.397V205.012C-100.122 226.114 -82.8477 243.388 -61.8494 243.388H11.5927C32.4875 243.388 49.4516 260.456 49.4516 281.247V497.643C49.4516 518.745 66.726 535.916 87.7243 535.916H403.836C424.938 535.916 442.108 518.641 442.108 497.643V-255.294C442.108 -276.396 424.834 -293.567 403.836 -293.567" fill="currentColor"/>
</svg>
