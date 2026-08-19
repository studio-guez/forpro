<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 513, height = 420 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 513 420" fill="none" aria-hidden="true" class={className}>
	<path d="M484.19 345.44L512.51 217.337C512.51 217.337 409.917 163.175 347.383 59.2064L79.5738 0.000776026L0.00019704 359.942L267.809 419.148C368.266 351.212 484.207 345.362 484.207 345.362" fill="currentColor"/>
</svg>
