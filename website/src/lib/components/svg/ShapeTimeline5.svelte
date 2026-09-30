<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="383"
	height="383"
	viewBox="0 0 383 383"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M364.646 108.287L278.89 194.042C255.491 217.442 217.724 217.688 194.612 194.576L187.92 187.885C164.809 164.773 165.014 127.047 188.454 103.607L274.21 17.8507C203.438 -15.3187 115.958 -2.22336 56.885 56.8493C-18.2797 132.014 -19.0598 253.074 55.1608 327.295C129.381 401.515 250.441 400.735 325.606 325.57C384.679 266.498 397.774 179.018 364.605 108.245"
		fill="currentColor"
	/>
</svg>
