<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- Only the top-left corner of the path is inside the viewBox: the shape is meant to be pinned bottom-right and clipped. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="222"
	height="144"
	viewBox="0 0 222 144"
	fill="none"
	aria-hidden="true"
	class={className}
>
	<path
		d="M365.565 -9.925e-05C365.565 -9.925e-05 284.514 75.1524 165.701 10.3077C165.701 10.3077 105.735 119.505 0.000293896 87.9574C0.000293896 87.9574 93.9914 160.638 15.4523 241.073C15.4523 241.073 161.959 175.782 235.866 270.792C235.866 270.792 276.434 142.81 365.437 158.76C365.437 158.76 289.965 103.814 365.53 0.0290491"
		fill="currentColor"
	/>
</svg>
