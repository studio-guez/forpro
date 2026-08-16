<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" width="28" height="29" viewBox="0 0 28 29" fill="none" aria-hidden="true" class={className}>
  <path d="M6 7.53125H22.5M6 14.75H22.5M6 21.9687H22.5" stroke="#1754FF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>