<script lang="ts">
	interface Props {
		title: string;
		text: string;
	}

	let { title, text }: Props = $props();
</script>

<section class="py-12 px-base text-center" aria-labelledby="page-intro-title">
	<h2 id="page-intro-title" class="text-h2 mb-12">{title}</h2>
	<div class="text-body-1 prose">
		{@html text}
	</div>
</section>
