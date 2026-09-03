<script lang="ts">
	interface Props {
		/** Why applications are closed, in the wording of the page it sits on. */
		message: string;
		class?: string;
	}

	let { message, class: className = '' }: Props = $props();
</script>

<p
	class={['text-h3 text-white border-3 bg-red rounded-2xl px-6 py-4 text-center', className]}
>
	{message}
</p>
