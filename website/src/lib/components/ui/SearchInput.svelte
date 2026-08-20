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
