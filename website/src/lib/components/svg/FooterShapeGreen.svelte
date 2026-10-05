<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- 1512×477 design-frame coordinates; the viewBox overshoots 188px right and 683px down, hence the parent's `bottom:-683px; right:-188px`. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="1010"
	height="970"
	viewBox="690 190 1010 970"
	aria-hidden="true"
	class={className}
>
	<path
		d="M1690.29 427.916L1587.84 303.783L1470.49 400.634C1323.36 223.673 1060.77 198.881 883.188 345.443C705.608 492.004 680.056 754.609 825.901 932.628L708.553 1029.48L811.003 1153.61L1690.29 427.916Z"
		class="fill-green"
	/>
</svg>
