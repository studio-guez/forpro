<script lang="ts">
	import IconSearch from '$lib/components/svg/IconSearch.svelte';

	interface Props {
		value: string;
		label: string;
		placeholder?: string;
		class?: string;
	}

	let { value = $bindable(''), label, placeholder = '', class: className = '' }: Props = $props();
</script>

<form
	role="search"
	class="flex justify-center {className}"
	onsubmit={(event) => event.preventDefault()}
>
	<label class="relative block w-full max-w-90">
		<span class="sr-only">{label}</span>
		<input
			type="search"
			bind:value
			{placeholder}
			class="search-input w-full rounded-full border-2 border-teal bg-transparent text-teal placeholder-teal font-bold text-lg px-5 py-3 pr-13 focus:border-teal focus:ring-teal"
		/>
		<IconSearch
			width={28}
			height={29}
			class="text-teal absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"
		/>
	</label>
</form>

<style>
	.search-input::-webkit-search-cancel-button {
		-webkit-appearance: none;
		appearance: none;
		width: 1em;
		height: 1em;
		/* currentColor = the input's text color (teal) */
		background-color: currentColor;
		mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='none' stroke='black' stroke-width='3' stroke-linecap='round' d='M5 5l14 14M19 5L5 19'/%3E%3C/svg%3E")
			no-repeat center / contain;
		cursor: pointer;
	}
</style>
