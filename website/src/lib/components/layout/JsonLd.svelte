<script lang="ts">
	interface Props {
		/** schema.org nodes, already assembled by the CMS. */
		schemas: Record<string, unknown>[];
	}

	let { schemas }: Props = $props();

	// Angle brackets are escaped: a CMS string holding a closing script tag would otherwise end the tag early (the tag's own brackets too, or they would end this component's script block).
	const tag = $derived.by(() => {
		if (schemas.length === 0) return '';

		const json = JSON.stringify(schemas.length === 1 ? schemas[0] : schemas).replaceAll(
			'\u003c',
			'\\u003c'
		);

		return `\u003cscript type="application/ld+json">${json}\u003c/script>`;
	});
</script>

<svelte:head>
	<!-- eslint-disable-next-line svelte/no-at-html-tags -- JSON serialized above, brackets escaped -->
	{@html tag}
</svelte:head>
