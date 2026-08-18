<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 376, height = 521 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 376 521" fill="none" aria-hidden="true" class={className}>
	<path d="M267.083 375.452V87.719C332.594 73.5857 375.794 49.4079 375.794 21.8523C375.794 -21.7032 267.438 -57.0811 133.749 -57.0811C0.060454 -57.0811 -108.295 -21.7032 -108.295 21.8523C-108.295 49.4079 -65.0951 73.5857 0.416009 87.719V375.452C-65.0951 389.586 -108.295 413.852 -108.295 441.319C-108.295 484.875 0.060454 520.252 133.749 520.252C267.438 520.252 375.794 484.875 375.794 441.319C375.794 413.763 332.594 389.586 267.083 375.452Z" fill="currentColor"/>
</svg>
