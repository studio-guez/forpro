<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import { slide } from 'svelte/transition';
	import type { Header } from '$lib/interfaces/global';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { socialIcons, socialLabels } from '$lib/utils/socials';

	interface Props {
		header: Header;
		/** Closes the burger menu the panel lives in, when a link is followed. */
		onNavigate: () => void;
	}

	let { header, onNavigate }: Props = $props();

	// The component is created when the burger menu opens, so the accordion always
	// starts collapsed without anything having to reset it.
	let openColumns = $state<number[]>([]);

	const toggleColumn = (index: number) => {
		openColumns = openColumns.includes(index)
			? openColumns.filter((i) => i !== index)
			: [...openColumns, index];
	};
</script>

<nav
	id="burger-menu-mobile"
	class="lg:hidden w-full max-w-360 mx-auto flex flex-col min-h-0 px-5 pt-4 pb-10"
	transition:slide={{ duration: 300 }}
	aria-label="Menu secondaire"
>
	<div data-lenis-prevent class="min-h-0 overflow-y-auto overscroll-contain border-t-2 border-blue">
		{#each header.secondaryMenu as column, i (i)}
			{@const expanded = !column.title || openColumns.includes(i)}
			<div>
				{#if column.title}
					<button
						type="button"
						class="w-full flex items-center justify-between gap-x-4 py-4 text-left text-2xl font-bold text-blue"
						aria-expanded={openColumns.includes(i)}
						aria-controls={expanded ? `burger-menu-column-${i}` : undefined}
						onclick={() => toggleColumn(i)}
					>
						<span class="text-trim">{column.title}</span>
						{#if openColumns.includes(i)}
							<IconClose class="shrink-0 w-7 h-7" />
						{:else}
							<IconPlus class="shrink-0 w-7 h-7" />
						{/if}
					</button>
				{/if}
				{#if expanded}
					<div
						id="burger-menu-column-{i}"
						class="flex flex-col gap-y-6 pb-6"
						transition:slide={{ duration: 300 }}
					>
						{#each column.groups as group, j (j)}
							{@const hasLevel2 = group.links.some((link) => link.level === 2)}
							<div>
								{#if group.title}
									<p class="text-label leading-none text-blue mb-0.75">{group.title}</p>
								{/if}
								<ul class="flex flex-col gap-1">
									{#each group.links as link (link)}
										<li
											class="leading-none {hasLevel2 && link.level !== 2
												? 'mb-0.75'
												: ''} {link.level === 2 ? 'pl-8' : ''}"
										>
											<a
												href={link.url}
												onclick={onNavigate}
												target={link.target ?? undefined}
												rel={link.target === '_blank' ? 'noopener noreferrer' : undefined}
												class="text-body-2 leading-none {link.level === 2
													? ''
													: hasLevel2
														? 'font-bold'
														: ''} hover:text-blue transition-colors"
											>
												{link.label}
											</a>
										</li>
									{/each}
								</ul>
							</div>
						{/each}
					</div>
				{/if}
			</div>
		{/each}
	</div>

	<div class="shrink-0 flex flex-col items-center border-t-2 border-blue pt-6">
		{#if header.externalLinks.length > 0}
			<ul class="flex flex-wrap justify-center gap-3 mb-6">
				{#each header.externalLinks as link (link)}
					<li class="leading-none bg-green rounded-full px-3 py-2">
						<a
							href={link.url}
							target="_blank"
							rel="noopener noreferrer"
							class="text-body-2 leading-none flex items-center gap-x-2"
						>
							<IconLink class="shrink-0" /> <span>{link.label}</span>
						</a>
					</li>
				{/each}
			</ul>
		{/if}

		{#if header.socialLinks.length > 0}
			<ul class="flex justify-center gap-3">
				{#each header.socialLinks as social, i (`${social.platform}-${i}`)}
					{@const Icon = socialIcons[social.platform]}
					<li>
						<a href={social.url} target="_blank" rel="noopener noreferrer" class="block">
							<span
								class="flex items-center justify-center h-10 w-10 rounded-full bg-blue text-white shrink-0"
							>
								<Icon class="h-6 w-6" />
							</span>
							<span class="sr-only">{socialLabels[social.platform]}</span>
						</a>
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</nav>
