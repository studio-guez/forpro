<script lang="ts">
	import PageHero from '$lib/components/PageHero.svelte';
	import PageIntro from '$lib/components/PageIntro.svelte';
	import BlockModuleTitreTexteImage from '$lib/components/BlockModuleTitreTexteImage.svelte';
	import BlockModuleCases from '$lib/components/BlockModuleCases.svelte';
	import BlockModuleInfosPratiques from '$lib/components/BlockModuleInfosPratiques.svelte';
	import BlockModuleAgenda from '$lib/components/BlockModuleAgenda.svelte';
	import BlockModuleProjets from '$lib/components/BlockModuleProjets.svelte';
	import type { Page, ModuleTitreTexteImageContent, ModuleCasesContent, ModuleInfosPratiquesContent, ModuleAgendaContent, ModuleProjetsContent } from '$lib/interfaces/page';

	let { page }: { page: Page } = $props();
</script>

<PageHero title={page.title} overtitle={page.overtitle} theme={page.theme} cover={page.cover} />

<PageIntro
	title={page.introTitle}
	text={page.intro}
	layout={page.introLayout}
	cta={page.introCta}
	parentPage={page.parentPage}
	theme={page.theme}
	titleImage={page.introTitleImage}
/>

{#each page.body as block (block.id)}
	{#if !block.isHidden}
		{#if block.type === 'module-titre-texte-image'}
			<BlockModuleTitreTexteImage
				content={block.content as unknown as ModuleTitreTexteImageContent}
				theme={page.theme}
			/>
		{:else if block.type === 'module-cases'}
			<BlockModuleCases
				content={block.content as unknown as ModuleCasesContent}
				theme={page.theme}
			/>
		{:else if block.type === 'module-infos-pratiques'}
			<BlockModuleInfosPratiques
				content={block.content as unknown as ModuleInfosPratiquesContent}
			/>
		{:else if block.type === 'module-agenda'}
			<BlockModuleAgenda
				content={block.content as unknown as ModuleAgendaContent}
			/>
		{:else if block.type === 'module-projets'}
			<BlockModuleProjets
				content={block.content as unknown as ModuleProjetsContent}
			/>
		{/if}
	{/if}
{/each}
