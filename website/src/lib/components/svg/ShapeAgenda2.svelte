<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 289, height = 476 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 289 476" fill="none" aria-hidden="true" class={className}>
	<path d="M138.506 475.264H160.642C237.187 475.264 299.147 413.304 299.147 336.758V28.5081L149.574 -235.573L0 28.5081V336.758C0 413.2 61.9603 475.264 138.506 475.264Z" fill="currentColor"/>
</svg>
