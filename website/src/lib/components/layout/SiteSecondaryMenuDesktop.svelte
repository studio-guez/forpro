<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import { slide } from 'svelte/transition';
	import type { Header } from '$lib/interfaces/global';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import { socialIcons, socialLabels } from '$lib/utils/socials';

	interface Props {
		header: Header;
		/** Closes the burger menu the panel lives in, when a link is followed. */
		onNavigate: () => void;
	}

	let { header, onNavigate }: Props = $props();
</script>

<nav
	id="burger-menu-desktop"
	data-lenis-prevent
	class="hidden lg:grid w-full max-w-360 mx-auto min-h-0 overflow-y-auto overscroll-contain px-9 py-12 grid-cols-5 items-start gap-x-8 gap-y-10"
	transition:slide={{ duration: 300 }}
	aria-label="Menu secondaire"
>
	{#each header.secondaryMenu as column, i (i)}
		<div>
			{#if column.title}
				<p class="text-body-2 font-bold text-blue pb-3 mb-6 border-b-2">{column.title}</p>
			{/if}
			<div class="flex flex-col gap-y-6">
				{#each column.groups as group, j (j)}
					{@const hasLevel2 = group.links.some((link) => link.level === 2)}
					<div>
						{#if group.title}
							<p class="text-label leading-none text-blue mb-0.75">{group.title}</p>
						{/if}
						<ul class="flex flex-col">
							{#each group.links as link (link)}
								<li class="leading-none {hasLevel2 && link.level !== 2 ? 'mb-0.75' : ''} {link.level === 2 ? 'pl-8' : ''}">
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
		</div>
	{/each}

	<div class="flex flex-col bg-green rounded-2xl p-4 -m-4">
		{#if header.externalLinksTitle}
			<p class="text-body-2 font-bold pb-3 mb-6 border-b-2">{header.externalLinksTitle}</p>
		{/if}
		{#if header.externalLinks.length > 0}
			<ul class="flex flex-col gap-y-3 mb-6">
				{#each header.externalLinks as link (link)}
					<li class="leading-none">
						<a
							href={link.url}
							target="_blank"
							rel="noopener noreferrer"
							class="text-body-2 leading-none flex items-center gap-x-2"
						>
							<IconLink class="shrink-0" /> <span class="underline">{link.label}</span>
						</a>
					</li>
				{/each}
			</ul>
		{/if}

		{#if header.socialLinks.length > 0}
			<ul class="flex flex-col gap-y-3">
				{#each header.socialLinks as social, i (`${social.platform}-${i}`)}
					{@const Icon = socialIcons[social.platform]}
					<li>
						<a
							href={social.url}
							target="_blank"
							rel="noopener noreferrer"
							class="flex items-center gap-x-2 text-body-2 leading-none hover:text-blue transition-colors"
						>
							<span class="flex items-center justify-center h-8 w-8 rounded-full bg-white text-blue shrink-0">
								<Icon class="h-6 w-6" />
							</span>
							<span>{socialLabels[social.platform]}</span>
						</a>
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</nav>
