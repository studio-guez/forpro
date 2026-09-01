<script lang="ts">
	interface Props {
		class?: string;
	}

	let { class: className = '' }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" width="37" height="38" viewBox="0 0 37 38" fill="none" aria-hidden="true" class={className}>
	<path d="M33.9163 11.084L20.0552 20.1517C19.5848 20.4323 19.0506 20.5801 18.5066 20.5801C17.9627 20.5801 17.4284 20.4323 16.958 20.1517L3.08301 11.084M6.16634 6.33398H30.833C32.5359 6.33398 33.9163 7.75175 33.9163 9.50065V28.5007C33.9163 30.2496 32.5359 31.6673 30.833 31.6673H6.16634C4.46346 31.6673 3.08301 30.2496 3.08301 28.5007V9.50065C3.08301 7.75175 4.46346 6.33398 6.16634 6.33398Z" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
