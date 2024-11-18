<script lang="ts">
	import Menu from '$lib/components/Menu.svelte';

	import {linkTreeIsOpen, menuIsOpen, resaButtonIsHidden} from '../store';
	import {onMount, tick} from "svelte";

	export let data;

	menuIsOpen.subscribe((value) => {
		console.log(value);
	});

	const handleMenuClick = () => {
		$menuIsOpen = !$menuIsOpen;
	};

	onMount(() => {
		tick().then(() => {
			const resaButton = document.querySelector('.s-button-resa')

			const observateur = new IntersectionObserver(entries => {
				entries.forEach(value => {
					$resaButtonIsHidden = !value.isIntersecting
				})
			})

			if(resaButton) observateur.observe(resaButton)

		})
	})
</script>

{#if $menuIsOpen}
<div class="fixed top-0 right-0 w-full h-full z-50"
>
	<Menu items={data.menu.content} />
	<div class="fixed top-0 right-0 w-full h-full"
			 style="background: rgba(0,0,0,.5);"
			 on:click={()=> $menuIsOpen = false}
	></div>
</div>
{/if}

<!-- Navbar -->
<nav class="px-3 pb-6 pt-3 lg:px-6">
	<div class="grid grid-cols-2">
		<!-- Title -->
		<div class="font-regular uppercase text-secondary">
			{@html data.page.menu.baseline}
		</div>
		<!-- Button -->
		<div class="flex justify-end">
			<button
				on:click={handleMenuClick}
				class="navbar-burger z-50 flex h-10 w-10 items-center rounded-full bg-secondary p-3 text-primary fixed top-4 right-4"
			>
				<svg
					class:hidden={$menuIsOpen}
					class="block fill-current size-4"
					viewBox="0 0 20 20"
					xmlns="http://www.w3.org/2000/svg"
				>
					<title>Mobile menu</title>
					<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
				</svg>
				<svg
					class:hidden={!$menuIsOpen}
					xmlns="http://www.w3.org/2000/svg"
					class="block fill-current size-4"
					stroke="currentColor"
					viewBox="0 0 24 24"
				>
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						stroke-width="3"
						d="M6 18L18 6M6 6l12 12"
					/>
				</svg>
			</button>
		</div>
	</div>
</nav>

{#if $linkTreeIsOpen }
	<div class="s-link-tree">
		<div style="
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: -1;
		"
				 on:click={() => linkTreeIsOpen.set(false)}
		></div>
		<a
						href="https://api.for-pro.ch/media/site/d9ab2d9844-1729500293/menu_2024-10-21_10-44-53.pdf"
						type="button"
						class="bottom-5 left-5 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						target="_blank"
		>
			Carte du FoodLab
		</a>

		<a
						href="https://menus.for-pro.ch"
						type="button"
						class="bottom-5 left-5 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						target="_blank"
		>
			Plat du jour FoodLab
		</a>

		<a
						href="https://menus.for-pro.ch/foodcourt"
						type="button"
						class="bottom-5 left-5 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						target="_blank"
		>
			Plats du jour FoodCourt
		</a>

		<a
						href="https://api.for-pro.ch/media/site/7a047d9699-1730810358/menu_popup.pdf"
						type="button"
						class="bottom-5 left-5 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						target="_blank"
		>
			Carte du PopUp
		</a>
	</div>
{/if}

<!-- Hero -->
<section class="relative block h-[60vh] w-full px-3 py-6 pt-5 lg:h-auto lg:px-6">
	<div class="flex h-full items-start justify-center">
		<!-- Grid -->
		<div class="h-full w-full grid-cols-12 grid-rows-2 lg:grid">
			<!-- Image CTA -->
			<div
				class="relative z-10 col-span-7 hidden aspect-video w-full rounded-2xl lg:block"
				style:background-image="url({data.page.hero.pictureURL1})"
				style:background-position="center"
				style:background-size="cover"
			>
				{#if data.page.hero.btn1.text}
				<a href={data.page.hero.btn1.link}
					 type="button"
					 class="absolute bottom-5 left-5 rounded-full text-secondary bg-primary px-6 py-1 text-sm font-semibold shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					 target={data.page.hero.btn1.target ? '_blank' : '_self'}
				>
					{data.page.hero.btn1.text}
				</a>
				{/if}
			</div>

			<!-- Logo Rounded -->
			<div class="col-span-5 -mt-28 hidden items-center justify-center lg:flex">
				<a href="https://for-pro.ch" target="_blank">
					<img class="w-40" src={data.page.hero.pictureURL2} alt="imaginé par forpro" />
				</a>
			</div>

			<!-- Text -->
			<div
				class="col-span-5 -mt-8 hidden items-center justify-center text-center text-2xl uppercase text-primary lg:flex"
			>
				{@html data.page.hero.text}
			</div>

			<!-- Image CTA -->
			<div
				class="s-button-resa relative col-span-7 -mt-20 hidden aspect-video w-full rounded-2xl lg:block"
				style:background-image="url({data.page.hero.pictureURL3})"
				style:background-position="center"
				style:background-size="cover"
			>
				<button
								on:click={() => linkTreeIsOpen.set(true)}
					type="button"
					class="absolute -top-3 right-10 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					class:is-hidden={$resaButtonIsHidden}
				>
					Cartes & Plats du jour
				</button>
			</div>
		</div>

		<!-- Badge overlay !-->
		<img
			class="absolute z-40 -mt-6 hidden h-[80%] lg:block lg:h-[90%]"
			src="/hero_overlay.svg"
			alt="overlay forpro"
		/>

		<a href="https://for-pro.ch" target="_blank">
			<img
				class="absolute -top-10 right-1 z-10 w-20 rotate-45 lg:hidden"
				src={data.page.hero.pictureURL2}
				alt="imaginé par forpro"
			/>
		</a>

		<div
			class="absolute -bottom-10 grid h-[70lvh] w-[90vw] grid-rows-[80%_20%] justify-center rounded-2xl py-5 lg:hidden"
			style:background-image="url({data.page.hero.pictureURL3})"
			style:background-position="center"
			style:background-size="cover"
		>
			<div class="flex items-center justify-center">
				<img
					class="relative z-40 -mt-6 h-[90%] lg:hidden"
					src="/hero_overlay.svg"
					alt="overlay forpro"
				/>
			</div>
			<div class="flex space-y-2 flex-col justify-end">
				{#if data.page.footer.btn1.text}
				<a
					href={data.page.hero.btn1.link}
					type="button"
					class="h-min text-center rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:hidden"
					target={data.page.hero.btn1.target ? '_blank' : '_self'}
				>
					{data.page.hero.btn1.text}
				</a>
					{/if}

				<button
								on:click={() => linkTreeIsOpen.set(true)}
								type="button"
								class="block -top-3 right-10 text-center rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
								class:is-hidden={$resaButtonIsHidden}
				>
					Cartes & Plats du jour
				</button>
			</div>
		</div>
	</div>
</section>

<section id="lefood" class="relative mt-24 w-full lg:mt-20">
	<!-- Background -->
	<img class="absolute -left-10 -top-10 z-10 lg:-left-10 lg:-top-10 lg:w-3/5" src="/bg_2_1.svg" />

	<!-- Container -->
	<div class="grid h-full w-full grid-cols-1 px-3 lg:grid-cols-12 lg:px-0">
		<!-- Title -->
		<div
			class="z-30 flex items-center justify-center text-5xl uppercase text-white lg:col-span-12 lg:text-6xl"
		>
			<span>{data.page.food.title}</span>
		</div>

		<div
			class="col-span-full mx-5 grid grid-cols-1 space-y-5 lg:mx-20 lg:-mt-8 lg:grid-cols-2 lg:space-y-0"
		>
			<div
				class="z-20 col-span-1 mt-5 aspect-square rounded-2xl lg:hidden"
				style:background-image="url({data.page.food.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>

			<!-- Text -->
			<div class="font-regular z-20 col-span-1 flex flex-col pt-0 text-primary lg:pt-10">
				<div class="prose-sm mt-3 leading-4 lg:mt-10 lg:pr-10">
					{@html data.page.food.text}
				</div>

				<div class="h-full self-center align-bottom lg:self-start">
					<a
						href={data.page.food.btn.link}
						type="button"
						class="mt-10 block w-max rounded-full bg-white px-14 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						target={data.page.food.btn.target ? '_blank' : '_self'}
					>
						{data.page.food.btn.text}
					</a>
				</div>
			</div>

			<!-- Image -->
			<div
				class="z-20 col-span-1 hidden aspect-square rounded-2xl lg:block"
				style:background-image="url({data.page.food.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>
		</div>
	</div>
</section>

<section id="lelab" class="relative mt-16 min-h-72 w-full bg-background pb-10 lg:mt-32 lg:pb-20 overflow-hidden">
	<!-- Container -->
	<div class="grid h-full w-full grid-cols-1 space-y-5 lg:grid-cols-12 lg:space-y-0">
		<!-- Title -->
		<div
			class="z-30 flex items-center justify-center text-5xl uppercase text-white lg:relative lg:top-4 lg:col-span-12 lg:text-6xl"
		>
			<span>{data.page.lab.title}</span>
		</div>

		<div class="mx-5 grid grid-cols-1 lg:col-span-full lg:mx-20 lg:-mt-10 lg:grid-cols-2">
			<!-- Image -->
			<div
				class="z-20 col-span-1 aspect-square rounded-2xl"
				style:background-image="url({data.page.lab.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>

			<!-- Text -->
			<div class="font-regular relative z-20 col-span-1 flex flex-col text-primary lg:pt-10">
				<div class="prose-sm mt-10 leading-4 lg:pl-10">
					{@html data.page.lab.text}
				</div>
				<div class="h-full align-bottom">
					{#if data.page.lab.btn.text}
					<a
						href={data.page.lab.btn.link}
						type="button"
						class="mt-10 block w-max rounded-full bg-white px-5 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:ml-10 lg:px-14"
						target={data.page.lab.btn.target ? '_blank' : '_self'}
					>
						{data.page.lab.btn.text}
					</a>
					{/if}
				</div>
			</div>
		</div>
	</div>

	<!-- Background -->
	<img
		class="absolute -right-20 bottom-20 z-10 lg:-bottom-52 lg:-right-52 lg:w-3/5"
		src="/bg_3_1.svg"
	/>
</section>

<section class="relative z-10 mt-10 w-full">
	<div
		class="min-h-96 rounded-3xl"
		style:background-image="url({data.page.highlight.pictureURL})"
		style:background-position="center"
		style:background-size="cover"
	></div>
</section>

<section id="equipe-formation" class="relative w-full rounded-3xl bg-primary pb-10 lg:pb-20">
	<!-- Container -->
	<div class="h-full w-full lg:grid lg:grid-cols-12">
		<!-- Title -->
		<div
			class="z-30 flex items-center justify-center pt-10 text-center text-3xl uppercase text-white lg:col-span-12 lg:mt-20 lg:pt-0 lg:text-6xl"
		>
			<span>{@html data.page.formation.title}</span>
		</div>

		<div class="mx-5 grid grid-cols-1 lg:col-span-full lg:mx-20 lg:-mt-8 lg:grid-cols-2">
			<!-- Text -->
			<div class="font-regular z-20 col-span-1 pt-12 text-center text-secondary lg:text-left">
				<div class="prose-sm mt-2 leading-4 lg:pr-10">
					<span>{@html data.page.formation.text}</span>
				</div>

				<a
					href={data.page.formation.btn.link}
					type="button"
					class="mt-10 rounded-full bg-secondary px-4 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 lg:px-14"
					target={data.page.formation.btn.target ? '_blank' : '_self'}
				>
					<span>{data.page.formation.btn.text}</span>
				</a>
			</div>

			<!-- Image -->
			<div
				class="z-40 col-span-1 mt-10 h-56 rounded-2xl brightness-90 lg:z-20 lg:mt-0 lg:h-auto"
				style:background-image="url({data.page.formation.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>
		</div>
	</div>
</section>

<section
	id="foodcourt-popup-cafe"
	class="relative mt-20 w-full bg-background px-3 pb-5 lg:px-0 lg:pb-20 overflow-hidden"
>
	<!-- Container -->
	<div class="relative z-20 h-full w-full lg:grid lg:grid-cols-12">
		<!-- Title -->
		<div
			class="z-30 col-span-12 mx-auto flex items-center justify-center text-center text-3xl text-primary lg:text-6xl"
		>
			<span>{data.page.univers.title}</span>
		</div>

		<!-- Description -->
		<div
			class="z-30 col-span-12 mx-auto mt-10 flex items-center justify-center text-center text-[0.875rem] text-primary lg:w-1/2 lg:text-xs"
		>
			<p>{data.page.univers.subtitle}</p>
		</div>

		<div
			class="z-30 col-span-12 mx-auto mt-10 flex items-center justify-center text-center text-primary"
		>
			<span class="text-xl font-bold">{data.page.univers.blogTitle1}</span>
		</div>

		<!-- Image -->
		<div
			class="relative z-30 col-span-1 mx-auto mt-5 grid min-h-0 grid-cols-12 items-center justify-center rounded-3xl lg:col-span-12"
		>
			<img
				class=" col-span-12 mx-auto h-auto rounded-2xl object-contain object-center lg:col-span-8 lg:col-start-3 lg:h-full lg:w-full lg:rounded-3xl"
				src={data.page.univers.blogPictureUrl1}
			/>
			<a href="https://for-pro.ch" target="_blank">
				<img
					class="absolute left-48 top-0 -ml-10 -mt-14 hidden w-40 lg:block"
					src="/hero_1_2.svg"
					alt="imaginé par forpro"
				/>
			</a>
		</div>

		<div
			class="col-span-1 mx-auto mt-5 grid grid-cols-1 gap-5 text-primary lg:col-span-8 lg:col-start-3 lg:mt-10 lg:grid-cols-2"
		>
			{@html data.page.univers.blogText1}
		</div>
	</div>

	<!-- Background -->
	<img class="absolute -right-32 top-20 z-10 w-[50em] lg:-right-64 lg:top-0" src="/bg_5_1.svg" />
</section>

<section class="relative mt-10 bg-background px-5 pb-20 lg:px-0">
	<!-- Container -->
	<div class="relative z-20 lg:grid lg:grid-cols-12">
		<div
			class="z-30 col-span-12 mx-auto mt-10 flex items-center justify-center text-center text-primary"
		>
			<span class="text-xl font-bold">{data.page.univers.blogTitle2}</span>
		</div>

		<!-- Image -->
		<div
			class="relative z-30 col-span-1 mx-auto mt-5 grid min-h-0 grid-cols-12 items-center justify-center rounded-3xl lg:col-span-12"
		>
			<img
				class=" col-span-12 mx-auto h-auto rounded-2xl object-contain object-center lg:col-span-8 lg:col-start-3 lg:h-full lg:w-full lg:rounded-3xl"
				src={data.page.univers.blogPictureUrl2}
			/>
			<a href="https://for-pro.ch" target="_blank">
				<img
					class="absolute right-36 top-0 -ml-10 -mt-14 hidden w-40 rotate-45 lg:block"
					src="/hero_1_2.svg"
					alt="imaginé par forpro"
				/>
			</a>
		</div>

		<!-- Description -->
		<div
			class="col-span-1 mx-auto mt-5 grid grid-cols-1 gap-5 text-primary lg:col-span-10 lg:col-start-3 lg:mt-10 lg:grid-cols-2"
		>
			{@html data.page.univers.blogText2}
		</div>
	</div>

	<!-- Background -->
	<img
		class="z-1 absolute left-[-6rem] top-[-0rem] lg:left-[-23rem] lg:top-[-5rem] lg:w-[175em]"
		src="/bg_5_2.svg"
	/>
</section>

<section id="engagements" class="relative w-full rounded-3xl bg-primary pb-20">
	<!-- Container -->
	<div class="grid h-full w-full lg:px-36">
		<!-- Title -->
		<div
			class="z-30 mt-20 items-center justify-center text-center text-3xl uppercase text-white lg:text-6xl"
		>
			<span>{data.page.values.title}</span>
		</div>

		<div class="-mt-8">
			<!-- Text -->
			<div class="font-regular z-20 pt-12 text-center text-white">
				<div class="mt-2 px-5 lg:pr-10">
					{@html data.page.values.text}
				</div>
			</div>
		</div>

		<div class="mt-10 grid grid-cols-1 justify-around lg:col-span-full lg:grid-cols-4 gap-12">
			{#each data.page.values.list as value}
				<div class="flex items-center justify-center mt-5 lg:mt-0">
					<div class="inline-grid h-full w-full justify-center text-center">
						<img src={value.icon} class="w-32 justify-self-center pb-3" />
						<span class="uppercase text-white">{@html value.title}</span>
					</div>
				</div>
			{/each}
		</div>
	</div>
</section>

<section class="relative w-full rounded-3xl bg-secondary px-5 pb-20 pt-10 lg:px-20">
	<div
		class="grid grid-cols-1 lg:grid-flow-row lg:auto-rows-min lg:grid-cols-4 lg:grid-rows-[1fr_auto] lg:gap-4"
	>
		<div class="col-span-1 flex items-center justify-center lg:items-start lg:justify-start">
			<img class="h-[25em]" src="/footer_logo.svg" alt="overlay forpro" />
		</div>

		<div class="col-span-1 w-full">
			<div class="prose-sm w-full text-center font-bold leading-4 text-primary lg:text-left">
				{@html data.page.footer.text1}
			</div>
			<div class="mt-10 grid justify-center space-y-5 lg:justify-normal">
				{#if data.page.footer.btn1.text}
				<a
					href={data.page.footer.btn1.link}
					type="button"
					class="w-36 rounded-full bg-primary px-6 py-1 text-center text-sm font-semibold text-secondary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					target={data.page.footer.btn1.target ? '_blank' : '_self'}
				>
					{data.page.footer.btn1.text}
				</a>
				{/if}

				{#if data.page.footer.btn2.text}
				<a
					href={data.page.footer.btn2.link}
					type="button"
					class="w-36 rounded-full bg-primary px-6 py-1 text-center text-sm font-semibold text-secondary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					target={data.page.footer.btn2.target ? '_blank' : '_self'}
				>
					{data.page.footer.btn2.text}
				</a>
				{/if}
			</div>
		</div>

		<div class="col-span-1 mt-10 text-center lg:col-span-2 lg:row-start-2 lg:text-left">
			<div class="prose-sm font-bold leading-4 text-primary">
				{@html data.page.footer.text2}
			</div>
		</div>

		<div
			class="relative mt-10 h-fit self-end rounded-3xl bg-background p-6 lg:col-span-2 lg:row-span-2 lg:ml-16 lg:py-16 lg:align-bottom"
		>
			<img
				class="absolute -left-14 -top-12 hidden w-36 -rotate-12 lg:block"
				src="/badge_footer.svg"
			/>
			<div class="prose-sm my-5 text-xs text-primary">
				{@html data.page.footer.text3}
			</div>
			<img src="/footer_logo_2.svg" class="w-full lg:w-44" />
		</div>
	</div>
</section>


<style>
	.s-link-tree {
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		box-sizing: border-box;
		display: flex;
		background: rgba(0, 0, 0, .85);
		z-index: 100;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		gap: 2rem;
	}

	.is-hidden {
		position: fixed;
		bottom: 1rem;
		top: auto;
		left: 50%;
		right: auto;
		transform: translate(-50%, 0);
		z-index: 50;
		height: auto;
		box-shadow: 0 10px 10px 0 rgba(0, 0, 0, .25);
	}
</style>
