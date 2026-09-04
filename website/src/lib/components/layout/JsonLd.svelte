<script lang="ts">
	interface Props {
		/** schema.org nodes, already assembled by the CMS. */
		schemas: Record<string, unknown>[];
	}

	let { schemas }: Props = $props();

	// A single node is emitted on its own, several as an array: both are valid
	// JSON-LD, and one script tag per page keeps the graph together.
	//
	// The payload only becomes a script through `{@html}`, so every opening
	// angle bracket it carries is escaped: a CMS string holding a closing script
	// tag would otherwise end the tag early. The brackets of the tag itself are
	// written as `\u003c` for the mirror reason — spelled out, a closing script
	// tag here would end this component's own script block.
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
