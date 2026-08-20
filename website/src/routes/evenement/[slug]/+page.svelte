<div class="s-event">
	<AppContentPage content={data?.pageInfo ?? null}>
		<svelte:fragment slot="meta">
			{#if data?.pageInfo?.dateStart}
				<div class="s-event__date">
					{#if hasEndDate}Du{/if}
					{@html formatDate(data.pageInfo.dateStart.date, true, !hasEndDate)}
					{#if data.pageInfo.dateStart.time}
						à {formatTime(data.pageInfo.dateStart.time)}
					{/if}
					{#if hasEndDate && data.pageInfo.dateEnd}
						<br />
						au {@html formatDate(data.pageInfo.dateEnd.date, true, false)}
						{#if data.pageInfo.dateEnd.time}
							à {formatTime(data.pageInfo.dateEnd.time)}
						{/if}
					{/if}
				</div>
			{/if}

			<AppContentTags tags={data?.pageInfo?.eventThemes ?? []} label="Thématiques" />
			<AppContentTags tags={data?.pageInfo?.domains ?? []} label="Domaines" />
		</svelte:fragment>
	</AppContentPage>
</div>

<script lang="ts">
	import type { IEventPage } from '$lib/interfaces/cmsApiResponse';
	import AppContentPage from '$lib/components/AppContentPage.svelte';
	import AppContentTags from '$lib/components/AppContentTags.svelte';
	import { formatDate } from '$lib/utils/formatDate';
	import { formatTime } from '$lib/utils/formatTime';

	export let data: IEventPage | null;

	$: hasEndDate =
		!!data?.pageInfo?.dateEnd && data.pageInfo.dateEnd.date !== data.pageInfo.dateStart?.date;
</script>

<style lang="scss">
	.s-event__date {
		padding: 0.4em 1.5em 0.6em;
		border-radius: 4em;
		background: var(--app-color--blue);
		color: white;
		font-weight: 800;
		line-height: 1.25em;

		&:first-letter {
			text-transform: uppercase;
		}
	}
</style>
