<script lang="ts">
	interface Props {
		title: string;
		hideTitle?: boolean;
		variant?: 'pill' | 'plain';
		/** Layout classes for the `<h2>` itself — alignment, gutters, stacking. */
		class?: string;
		/** Appearance of the pill when it comes from the theme rather than the payload. */
		pillClass?: string;
		/** Colours coming from the CMS payload, applied inline on the pill. */
		background?: string | null;
		color?: string | null;
		/** Set when the title labels a landmark through `aria-labelledby`. */
		id?: string;
	}

	let {
		title,
		hideTitle = false,
		variant = 'pill',
		class: className = '',
		pillClass = '',
		background = null,
		color = null,
		id = undefined
	}: Props = $props();

	const style = $derived(
		[background && `background-color: ${background}`, color && `color: ${color}`]
			.filter(Boolean)
			.join('; ')
	);
</script>

{#if hideTitle}
	<h2 {id} class="sr-only">{title}</h2>
{:else if variant === 'plain'}
	<h2 {id} class={['text-h2', className]} {style}>{title}</h2>
{:else}
	<h2 {id} class={['text-h3', className]}>
		<span class={['inline-block rounded-2xl px-8 pt-2 pb-3.5', pillClass]} {style}>{title}</span>
	</h2>
{/if}
