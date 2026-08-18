<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 459, height = 567 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 459 567" fill="none" aria-hidden="true" class={className}>
	<path d="M126.147 515.734L48.9662 413.919L314.239 212.829L391.42 314.644L458.044 264.139L8.39258 -329.031L-58.2319 -278.527L15.9158 -180.713L-249.357 20.3772L-323.505 -77.4367L-390.048 -26.994L59.6038 566.176L126.147 515.734Z" fill="currentColor"/>
</svg>
