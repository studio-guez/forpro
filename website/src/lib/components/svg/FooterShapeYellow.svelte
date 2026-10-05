<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- 1512px design-frame coordinates; the viewBox starts 280px left of the frame, hence the parent's `top:0; left:-280px`. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	width="980"
	height="800"
	viewBox="-280 0 980 800"
	aria-hidden="true"
	class={className}
>
	<path
		d="M-247.004 461.391L249.078 784.696C271.442 799.271 301.831 792.863 316.406 770.498L635.993 280.122C650.641 257.647 644.234 227.258 621.87 212.683L543.651 161.707C521.398 147.203 515.177 117.25 529.609 95.1056L679.817 -135.374C694.465 -157.849 687.986 -188.128 665.622 -202.703L328.952 -422.118C306.478 -436.764 276.199 -430.284 261.624 -407.919L-261.089 394.134C-275.737 416.609 -269.258 446.887 -246.894 461.462"
		class="fill-yellow"
	/>
</svg>
