<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="128"
	height="122"
	viewBox="0 0 128 122"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M128.738 25.3788L103.016 -8.93597e-05L8.81176e-05 -8.03538e-05L8.95362e-05 16.2261L17.7696 33.7599L9.26019e-05 51.2936L9.35576e-05 62.2254L65.7194 127.066L76.7798 127.066L94.5494 109.532L112.319 127.066L128.738 127.066L128.738 25.3788Z"
		fill="currentColor"
	/>
</svg>
