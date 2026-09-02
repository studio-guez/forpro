<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg
	xmlns="http://www.w3.org/2000/svg"
	width="32"
	height="32"
	viewBox="0 0 32 32"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M12 9.7v12.6a1 1 0 0 0 1.53.85l9.9-6.3a1 1 0 0 0 0-1.7l-9.9-6.3A1 1 0 0 0 12 9.7Z"
		fill="currentColor"
	/>
</svg>
