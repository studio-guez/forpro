<script lang="ts">
	import IconSearch from '$lib/components/svg/IconSearch.svelte';

	interface Props {
		value: string;
		label: string;
		placeholder?: string;
		color?: string;
		class?: string;
	}

	let {
		value = $bindable(''),
		label,
		placeholder = '',
		color = 'teal',
		class: className = ''
	}: Props = $props();
</script>

<form
	role="search"
	class="flex justify-center {className}"
	onsubmit={(event) => event.preventDefault()}
>
	<label class="relative block w-full max-w-90" style:color={`var(--color-${color})`}>
		<span class="sr-only">{label}</span>
		<input
			type="search"
			bind:value
			{placeholder}
			class="search-input w-full rounded-full border-2 border-current bg-transparent text-current placeholder-current font-bold text-lg px-5 py-3 pr-13 focus:border-current focus:ring-current"
		/>
		<IconSearch
			width={28}
			height={29}
			class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"
		/>
	</label>
</form>

<style>
	.search-input::-webkit-search-cancel-button {
		-webkit-appearance: none;
		appearance: none;
		width: 1em;
		height: 1em;
		/* currentColor = the color prop */
		background-color: currentColor;
		mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='none' stroke='black' stroke-width='3' stroke-linecap='round' d='M5 5l14 14M19 5L5 19'/%3E%3C/svg%3E")
			no-repeat center / contain;
		cursor: pointer;
	}
</style>
