<script lang="ts">
	import { page } from '$app/state';
	import BackLink from '$lib/components/ui/BackLink.svelte';

	interface ErrorCopy {
		readonly title: string;
		readonly description: string;
	}

	const fallback: ErrorCopy = {
		title: 'Une erreur est survenue',
		description: 'Quelque chose s’est mal passé. Merci de réessayer dans quelques instants.'
	};

	const copyByStatus: Record<number, ErrorCopy> = {
		400: {
			title: 'Requête incorrecte',
			description: 'La requête envoyée n’a pas pu être traitée. Vérifiez l’adresse et réessayez.'
		},
		401: {
			title: 'Accès non autorisé',
			description: 'Vous devez être authentifié pour consulter cette page.'
		},
		403: {
			title: 'Accès refusé',
			description: 'Vous n’avez pas les droits nécessaires pour consulter cette page.'
		},
		404: {
			title: 'Page introuvable',
			description: 'La page que vous cherchez n’existe pas ou a été déplacée.'
		},
		408: {
			title: 'Délai d’attente dépassé',
			description: 'Le serveur a mis trop de temps à répondre. Merci de réessayer.'
		},
		410: {
			title: 'Page supprimée',
			description: 'Cette page n’est plus disponible et n’a pas de remplacement.'
		},
		429: {
			title: 'Trop de requêtes',
			description: 'Vous avez effectué trop de requêtes. Merci de patienter un instant.'
		},
		500: {
			title: 'Erreur interne',
			description:
				'Une erreur inattendue est survenue de notre côté. Nous travaillons à la résoudre.'
		},
		502: {
			title: 'Service indisponible',
			description:
				'Le site est momentanément indisponible. Merci de réessayer dans quelques instants.'
		},
		503: {
			title: 'Service indisponible',
			description:
				'Le site est momentanément indisponible. Merci de réessayer dans quelques instants.'
		},
		504: {
			title: 'Service indisponible',
			description:
				'Le site est momentanément indisponible. Merci de réessayer dans quelques instants.'
		}
	};

	// SvelteKit fills these in itself and they are always English, so they must not be shown.
	const isGenericMessage = (message: string) =>
		message === 'Not Found' || message === 'Internal Error' || /^Error: \d+$/.test(message);

	const copy = $derived(copyByStatus[page.status] ?? fallback);

	const message = $derived(page.error?.message ?? '');

	const description = $derived(
		message && !isGenericMessage(message) && message !== copy.title ? message : copy.description
	);
</script>

<svelte:head>
	<title>{page.status} — {copy.title}</title>
	<meta name="robots" content="noindex, nofollow" />
</svelte:head>

<section
	class="max-w-360 mx-auto px-5 md:px-9 pt-20 lg:pt-50 lg:grid lg:grid-cols-2 gap-x-8 gap-y-10"
>
	<div class="grid items-center lg:justify-center max-lg:mb-6">
		<svg
			xmlns="http://www.w3.org/2000/svg"
			width="403"
			height="340"
			viewBox="0 0 403 340"
			fill="none"
			class="col-start-1 row-start-1 max-w-1/2 lg:max-w-full h-auto"
		>
			<path
				d="M354.73 239.299L402.275 63.3279L167.892 0.000679354L77.1154 164.292L54.3692 158.146C38.0363 153.733 21.2168 163.397 16.8038 179.73L-0.000335384 241.924L359.556 339.072L376.36 276.877C380.773 260.544 371.109 243.725 354.776 239.312"
				class="fill-pink"
			/>
		</svg>
		<p
			class="col-start-1 row-start-1 text-center text-[15vw] lg:text-9xl font-bold text-green leading-none max-lg:max-w-1/2"
		>
			{page.status}
		</p>
	</div>
	<div class="text-pink">
		<h1 class="text-body-1 mb-3">{copy.title}</h1>
		<p class="text-h2 mb-3 lg:mb-24">{description}</p>
		<BackLink parentPage={{ path: '', slug: '', title: "l'accueil" }} color="var(--color-pink)" />
	</div>
</section>
