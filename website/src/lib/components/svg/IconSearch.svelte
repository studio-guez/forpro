<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 28, height = 29 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" width={width} height={height} viewBox="0 0 28 29" fill="none" aria-hidden="true" class={className}>
  <path d="M23.8253 24.2003L18.5217 18.8967M20.6667 13.7083C20.6667 17.7584 17.3834 21.0417 13.3333 21.0417C9.28324 21.0417 6 17.7584 6 13.7083C6 9.65824 9.28324 6.375 13.3333 6.375C17.3834 6.375 20.6667 9.65824 20.6667 13.7083Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>