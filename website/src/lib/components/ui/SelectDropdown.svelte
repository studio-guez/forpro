<script lang="ts">
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

	interface Option {
		readonly value: string;
		readonly label: string;
	}

	interface Props {
		value: string;
		options: Option[];
		label: string;
		/** Label of the option clearing the selection. */
		allLabel?: string;
		color?: string;
		class?: string;
	}

	let {
		value = $bindable(''),
		options,
		label,
		allLabel = 'Tous',
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();

	const uid = $props.id();
</script>

<div style:--select-color={color} class="text-(--select-color) {className}">
	<label class="sr-only" for="select-{uid}">{label}</label>
	<div class="relative inline-block">
		<select
			id="select-{uid}"
			bind:value
			class="text-label appearance-none bg-transparent border-0 border-b-2 border-current text-current pl-0 pr-9 py-1 focus:border-current focus:ring-0"
		>
			<option value="">{value === '' ? label : allLabel}</option>
			{#each options as option (option.value)}
				<option value={option.value}>{option.label}</option>
			{/each}
		</select>
		<IconChevron
			width={20}
			height={21}
			class="absolute right-1 top-1/2 -translate-y-1/2 pointer-events-none"
		/>
	</div>
</div>
