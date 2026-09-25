<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- The box's top and right edges coincide with the shape's, so the parent anchors it top-right. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="381"
	height="205"
	viewBox="0 0 381 205"
	aria-hidden="true"
	class={className}
>
	<path
		d="M-68.4071 -63.8641L-79.3073 -0.555115L-19.4586 9.74928C-34.7168 100.327 26.1232 186.243 116.691 201.836C207.259 217.429 293.375 156.816 309.288 66.351L369.137 76.6554L380.037 13.3464L-68.4071 -63.8641Z"
		class="fill-green"
	/>
</svg>
