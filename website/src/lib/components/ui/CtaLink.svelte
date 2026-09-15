<script lang="ts">
	import type { PageCta } from '$lib/interfaces/page';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPhone from '$lib/components/svg/IconPhone.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';
	import {
		CTA_BASE,
		CTA_LABEL,
		ctaColorClasses,
		ctaIconSizeClasses,
		ctaSizeClasses,
		type CtaSize
	} from '$lib/utils/ctaStyles';

	interface Props {
		cta: PageCta;
		color?: string;
		inverted?: boolean;
		size?: CtaSize;
		/** Accessible name, when the label alone is ambiguous (a repeated "Détails" in a list). */
		ariaLabel?: string;
		class?: string;
	}

	let {
		cta,
		color = 'var(--color-blue)',
		inverted = false,
		size = 'md',
		ariaLabel,
		class: className = ''
	}: Props = $props();

	const colorClasses = $derived(ctaColorClasses(inverted));
</script>

<a
	href={cta.url}
	target={cta.target ?? undefined}
	rel={cta.target === '_blank' ? 'noopener noreferrer' : undefined}
	download={cta.download ? '' : undefined}
	aria-label={ariaLabel}
	title={cta.label}
	style:--color-cta={color}
	class="{CTA_BASE} {colorClasses} {ctaSizeClasses[size]} {className}"
>
	<span class={CTA_LABEL}>{cta.label}</span>
	{#if cta.icon === 'arrow'}<IconArrow class={ctaIconSizeClasses[size]} />
	{:else if cta.icon === 'email'}<IconEmail class={ctaIconSizeClasses[size]} />
	{:else if cta.icon === 'phone'}<IconPhone class={ctaIconSizeClasses[size]} />
	{:else if cta.icon === 'plus'}<IconPlus class={ctaIconSizeClasses[size]} />
	{/if}
</a>
