<script lang="ts">
	import PageHeaderCompact from '$lib/components/blocks/PageHeaderCompact.svelte';
	import CardSmall2Cols from '$lib/components/ui/CardSmall2Cols.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import type { PressPage } from '$lib/interfaces/press';

	let { page }: { page: PressPage } = $props();
</script>

<PageHeaderCompact title={page.title} />

<CardSmall2Cols title={page.contactTitle ?? 'Contact :'} secondTitle={page.resourcesTitle}>
	{#snippet first()}
		<ul class="mt-6 space-y-6 text-body-2">
			{#each page.contactPersons as person, i (i)}
				<li>
					<p>{person.name}</p>
					{#if person.role}
						<p class="font-bold">{person.role}</p>
					{/if}
					{#if person.email}
						<a
							href="mailto:{person.email}"
							class="mt-4 inline-block underline underline-offset-4 hover:opacity-70 transition"
						>
							{person.email}
						</a>
					{/if}
					{#if person.phone}
						<p>
							<a
								href="tel:{person.phone.replace(/\s+/g, '')}"
								class="underline underline-offset-4 hover:opacity-70 transition"
							>
								{person.phone}
							</a>
						</p>
					{/if}
				</li>
			{/each}
		</ul>
	{/snippet}

	{#snippet second()}
		{#if page.resources}
			<a
				href={page.resources.url}
				download={page.resources.filename}
				class="text-label inline-flex items-center gap-3 rounded-full border-2 border-white px-6 py-2 hover:bg-white hover:text-blue transition"
			>
				{page.resourcesCtaTitle ?? 'Télécharger les ressources'}
				<IconArrow class="rotate-90 w-5 h-5" />
				<span class="sr-only">({page.resources.extension.toUpperCase()}, {page.resources.size})</span>
			</a>
		{/if}
	{/snippet}
</CardSmall2Cols>
