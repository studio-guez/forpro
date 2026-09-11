<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<!-- Stretched (`preserveAspectRatio="none"`) rather than scaled: the shapes are abstract, and
	 what has to hold is that the yellow still reaches the left edge and the green the right one,
	 so the flanking bands beside the content column continue them seamlessly at any height. -->
<svg
	xmlns="http://www.w3.org/2000/svg"
	viewBox="0 0 1512 477"
	preserveAspectRatio="none"
	aria-hidden="true"
	class={className}
>
	<g clip-path="url(#clip0_4998_18460)">
		<path
			d="M-247.004 461.391L249.078 784.696C271.442 799.271 301.831 792.863 316.406 770.498L635.993 280.122C650.641 257.647 644.234 227.258 621.87 212.683L543.651 161.707C521.398 147.203 515.177 117.25 529.609 95.1056L679.817 -135.374C694.465 -157.849 687.986 -188.128 665.622 -202.703L328.952 -422.118C306.478 -436.764 276.199 -430.284 261.624 -407.919L-261.089 394.134C-275.737 416.609 -269.258 446.887 -246.894 461.462"
			class="fill-yellow"
		/>
		<path
			d="M1690.29 427.916L1587.84 303.783L1470.49 400.634C1323.36 223.673 1060.77 198.881 883.188 345.443C705.608 492.004 680.056 754.609 825.901 932.628L708.553 1029.48L811.003 1153.61L1690.29 427.916Z"
			class="fill-green"
		/>
	</g>
	<defs>
		<clipPath id="clip0_4998_18460">
			<rect width="1512" height="477" fill="white" />
		</clipPath>
	</defs>
</svg>
