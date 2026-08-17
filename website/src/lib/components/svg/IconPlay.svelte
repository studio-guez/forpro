<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 119, height = 119 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 119 119" fill="none" aria-hidden="true" class={className}>
	<path d="M59.1606 3.40039C87.7473 3.40039 110.921 26.5745 110.921 55.1611C110.921 83.7478 87.7473 106.922 59.1606 106.922C30.574 106.922 7.3999 83.7478 7.3999 55.1611C7.3999 26.5745 30.574 3.40039 59.1606 3.40039ZM51.6089 35.2021C48.2757 33.2781 44.109 35.6835 44.1089 39.5322V72.4736C44.109 76.3224 48.2757 78.7278 51.6089 76.8037L80.1372 60.333C83.4705 58.4085 83.4705 53.5974 80.1372 51.6729L51.6089 35.2021Z" fill="currentColor"/>
</svg>
