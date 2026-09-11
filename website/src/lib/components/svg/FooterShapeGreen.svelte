<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- Fixed size, never stretched: the coordinates are those of the 1512×477 desktop design
	 frame. The viewBox ends 188px past the frame's right edge and 683px below its bottom, so the
	 parent anchors the element at `bottom:-683px; right:-188px` of the content column. -->
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
