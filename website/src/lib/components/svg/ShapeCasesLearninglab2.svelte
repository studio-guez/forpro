<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 532, height = 342 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 532 342" fill="none" aria-hidden="true" class={className}>
	<path d="M805.557 184.936L604.687 -263.807C591.29 -293.735 572.337 -299.132 542.662 -285.721L364.517 -205.212C271.346 -163.105 262.629 -67.4485 239.239 11.1157C208.542 114.095 149.412 171.012 46.1607 217.674L-0.000913166 238.536L30.3852 306.418L76.5469 285.557C179.798 238.895 261.412 232.205 358.459 277.453C432.573 311.952 509.491 368.909 602.661 326.802L780.807 246.293C810.482 232.882 819.038 215.053 805.641 185.125" fill="currentColor"/>
</svg>
