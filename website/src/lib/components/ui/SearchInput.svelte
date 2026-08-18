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
	<label class="relative block w-full max-w-70">
		<span class="sr-only">{label}</span>
		<input
			type="search"
			bind:value
			{placeholder}
			class="w-full rounded-full border-2 border-teal bg-transparent text-teal placeholder-teal font-bold text-lg px-5 py-3 pr-13 focus:border-teal focus:ring-teal"
		/>
		<IconSearch
			width={28}
			height={29}
			class="text-teal absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"
		/>
	</label>
</form>
