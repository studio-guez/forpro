<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" width="31" height="32" viewBox="0 0 31 32" fill="none" aria-hidden="true" class={className}>
  <path d="M22.2865 10.021L9.55139 22.821M9.55139 10.021L22.2865 22.821" stroke="#1754FF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
