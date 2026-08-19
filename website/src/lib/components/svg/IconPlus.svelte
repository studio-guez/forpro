<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 37, height = 38 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 37 38" fill="none" aria-hidden="true" class={className}>
	<path d="M7.70825 19.0001H29.2916M18.4999 7.91675V30.0834" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
