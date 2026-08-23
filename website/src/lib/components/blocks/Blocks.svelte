<script lang="ts">
	import BlockModuleTitreTexteImage from '$lib/components/blocks/BlockModuleTitreTexteImage.svelte';
	import BlockModuleCases from '$lib/components/blocks/BlockModuleCases.svelte';
	import BlockModuleGrilleImages from '$lib/components/blocks/BlockModuleGrilleImages.svelte';
	import BlockModuleVideo from '$lib/components/blocks/BlockModuleVideo.svelte';
	import BlockModuleInfosPratiques from '$lib/components/blocks/BlockModuleInfosPratiques.svelte';
	import BlockModuleTimeline from '$lib/components/blocks/BlockModuleTimeline.svelte';
	import BlockModuleAgenda from '$lib/components/blocks/BlockModuleAgenda.svelte';
	import BlockModuleProjets from '$lib/components/blocks/BlockModuleProjets.svelte';
	import BlockModuleCta from '$lib/components/blocks/BlockModuleCta.svelte';
	import BlockModulePartenaires from '$lib/components/blocks/BlockModulePartenaires.svelte';
	import type {
		Block,
		Theme,
		ModuleTitreTexteImageContent,
		ModuleCasesContent,
		ModuleGrilleImagesContent,
		ModuleVideoContent,
		ModuleInfosPratiquesContent,
		ModuleTimelineContent,
		ModuleAgendaContent,
		ModuleProjetsContent,
		ModuleCtaContent,
		ModulePartenairesContent
	} from '$lib/interfaces/page';

	let { blocks, theme = 'default' }: { blocks: Block[]; theme?: Theme } = $props();
</script>

{#each blocks as block (block.id)}
	{#if !block.isHidden}
		{#if block.type === 'module-titre-texte-image'}
			<BlockModuleTitreTexteImage
				content={block.content as unknown as ModuleTitreTexteImageContent}
				{theme}
			/>
		{:else if block.type === 'module-cases'}
			<BlockModuleCases content={block.content as unknown as ModuleCasesContent} {theme} />
		{:else if block.type === 'module-grille-images'}
			<BlockModuleGrilleImages
				content={block.content as unknown as ModuleGrilleImagesContent}
				{theme}
			/>
		{:else if block.type === 'module-video'}
			<BlockModuleVideo content={block.content as unknown as ModuleVideoContent} {theme} />
		{:else if block.type === 'module-infos-pratiques'}
			<BlockModuleInfosPratiques content={block.content as unknown as ModuleInfosPratiquesContent} />
		{:else if block.type === 'module-timeline'}
			<BlockModuleTimeline content={block.content as unknown as ModuleTimelineContent} />
		{:else if block.type === 'module-agenda'}
			<BlockModuleAgenda content={block.content as unknown as ModuleAgendaContent} />
		{:else if block.type === 'module-projets'}
			<BlockModuleProjets content={block.content as unknown as ModuleProjetsContent} />
		{:else if block.type === 'module-cta'}
			<BlockModuleCta content={block.content as unknown as ModuleCtaContent} />
		{:else if block.type === 'module-partenaires'}
			<BlockModulePartenaires content={block.content as unknown as ModulePartenairesContent} />
		{/if}
	{/if}
{/each}
