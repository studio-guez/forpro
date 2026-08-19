<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 613, height = 524 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 613 524" fill="none" aria-hidden="true" class={className}>
	<path d="M804.987 -157.715L0 167.721C66.2571 331.612 221.24 432.923 387.271 438.862L421.316 523.075L749.002 390.6L714.957 306.387C830.117 186.77 871.244 6.17629 804.987 -157.715Z" fill="currentColor"/>
</svg>
