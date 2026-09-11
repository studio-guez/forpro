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
	viewBox="0 0 402 600"
	preserveAspectRatio="none"
	aria-hidden="true"
	class={className}
>
	<g clip-path="url(#clip0_4998_14871)">
		<path
			d="M419.584 907.598L372.937 518.863C370.834 501.339 354.687 488.652 337.162 490.755L-47.1017 536.865C-64.7134 538.978 -77.4009 555.125 -75.298 572.649L-67.9432 633.942C-65.8507 651.38 -78.3966 667.247 -95.7493 669.33L-276.356 691.002C-293.967 693.115 -306.569 709.251 -304.466 726.776L-272.809 990.594C-270.696 1008.21 -254.559 1020.81 -237.033 1018.7L391.463 943.286C409.075 941.173 421.676 925.036 419.573 907.512"
			class="fill-yellow"
		/>
		<path
			d="M-68.4071 -63.8641L-79.3073 -0.555115L-19.4586 9.74928C-34.7168 100.327 26.1232 186.243 116.691 201.836C207.259 217.429 293.375 156.816 309.288 66.351L369.137 76.6554L380.037 13.3464L-68.4071 -63.8641Z"
			class="fill-green"
		/>
	</g>
	<defs>
		<clipPath id="clip0_4998_14871">
			<rect width="402" height="600" fill="white" />
		</clipPath>
	</defs>
</svg>
