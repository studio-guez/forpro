<div class="s-project">
	<AppContentPage content={data?.pageInfo ?? null}>
		<svelte:fragment slot="meta">
			<AppContentTags tags={data?.pageInfo?.projectTypes ?? []} label="Types" />
			<AppContentTags tags={data?.pageInfo?.projectThemes ?? []} label="Thématiques" />
		</svelte:fragment>

		<svelte:fragment slot="details">
			{#if data?.pageInfo?.collectiveName || members.length > 0}
				<section class="s-project__collective">
					{#if data?.pageInfo?.collectiveName}
						<h2 class="s-project__collective__title fp-heading-h4">
							{data.pageInfo.collectiveName}
						</h2>
					{/if}
					{#if members.length > 0}
						<ul class="s-project__collective__members">
							{#each members as member, index (index)}
								<li class="s-project__collective__members__item">{member.name}</li>
							{/each}
						</ul>
					{/if}
				</section>
			{/if}
		</svelte:fragment>
	</AppContentPage>
</div>

<script lang="ts">
	import type { IProjectPage } from '$lib/interfaces/cmsApiResponse';
	import AppContentPage from '$lib/components/AppContentPage.svelte';
	import AppContentTags from '$lib/components/AppContentTags.svelte';

	export let data: IProjectPage | null;

	$: members = data?.pageInfo?.collectiveMembers ?? [];
</script>

<style lang="scss">
	.s-project__collective__title {
		margin: 0 0 0.5rem;
	}

	.s-project__collective__members {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin: 0;
		padding: 0;
		list-style: none;
	}

	.s-project__collective__members__item {
		padding: 0.2em 0.9em 0.3em;
		border-radius: 4em;
		background: var(--app-color--pink);
		color: white;
		font-size: 0.875rem;
	}
</style>
