<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 560, height = 575 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 560 575" fill="none" aria-hidden="true" class={className}>
	<path d="M191.424 574.292L217.155 539.075C264.67 568.972 327.681 557.519 361.277 511.537L755.283 -27.7333C788.939 -73.7991 780.926 -137.62 738.227 -174.11L764.018 -209.41L584.792 -341.555L559.001 -306.254C511.485 -336.152 448.474 -324.698 414.878 -278.716L20.873 260.554C-12.7839 306.62 -4.76984 370.441 37.929 406.931L12.1985 442.148L191.424 574.292Z" fill="currentColor"/>
</svg>
