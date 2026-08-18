<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 322, height = 235 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 322 235" fill="none" aria-hidden="true" class={className}>
	<path d="M15.3927 2C-36.5297 104.609 67.3137 171.371 244.098 192.385M130.706 207.108C157.804 217.947 227.581 236.916 319.036 231.496" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
</svg>
