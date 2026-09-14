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

<!-- Drawn as the dropdowns are (`SelectDropdown`, `FilterDropdown`): the same
     underlined row, the icon where their chevron sits, pulled out by the same
     margin so it lines up with them in a toolbar. Full width on narrow screens,
     a fixed field otherwise, so it sits at the end of the row without stretching. -->
<form
	role="search"
	style:color={`var(--color-${color})`}
	style:--search-tint="color-mix(in oklab, var(--color-{color}) 12%, transparent)"
	class={['-mx-3 w-full sm:w-auto', className]}
	onsubmit={(event) => event.preventDefault()}
>
	<!-- Focus tints the field the way hovering a dropdown trigger does, so the
	     field in use reads the same across the toolbar. -->
	<label
		class="block w-full sm:w-96 px-3 py-2 rounded-xl transition-colors focus-within:bg-(--search-tint)"
	>
		<span class="sr-only">{label}</span>
		<div class="border-b-2 border-current flex items-center gap-3 pb-1">
			<input
				type="search"
				bind:value
				{placeholder}
				class="text-label flex-1 min-w-0 p-0 border-0 bg-transparent text-current placeholder-current/50 focus:ring-0 focus:outline-none"
			/>
			<IconSearch class="shrink-0 w-5.25 h-5.25 pointer-events-none" />
		</div>
	</label>
</form>
