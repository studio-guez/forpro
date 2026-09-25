<script lang="ts">
	import {page} from '$app/stores';
</script>

<div class="flex min-h-screen flex-col items-center justify-center gap-6 p-6 text-center text-secondary">
	<h1 class="text-6xl font-regular">{$page.status}</h1>
	<p>{$page.error?.message}</p>
	<a href="/" class="rounded-full bg-secondary px-6 py-3 text-primary">Retourner à l'accueil</a>
</div>
