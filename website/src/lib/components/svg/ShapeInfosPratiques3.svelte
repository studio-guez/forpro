<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 486, height = 444 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 486 444" fill="none" aria-hidden="true" class={className}>
	<path d="M383.744 22.8033L141.082 0.000825828L0.000189749 198.781L101.588 420.294L344.25 443.097L485.325 244.386L383.744 22.8033Z" fill="currentColor"/>
</svg>
