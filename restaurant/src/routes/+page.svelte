<script lang="ts">
	import Menu from '$lib/components/Menu.svelte';

	import { menuIsOpen } from '../store';

	export let data;

	menuIsOpen.subscribe((value) => {
		console.log(value);
	});

	const handleMenuClick = () => {
		$menuIsOpen = !$menuIsOpen;
	};
</script>

<Menu />

<!-- Navbar -->
<nav class="px-6 pb-6 pt-3">
	<div class="grid grid-cols-2">
		<!-- Title -->
		<div class="text-secondary font-regular uppercase">
			{@html data.page.menu.baseline}
		</div>
		<!-- Button -->
		<div class="flex justify-end">
			<button
				on:click={handleMenuClick}
				class="z-50 w-10 h-10 rounded-full bg-secondary navbar-burger flex items-center text-primary p-3"
			>
				<svg
					class:hidden={$menuIsOpen}
					class="block fill-current"
					viewBox="0 0 20 20"
					xmlns="http://www.w3.org/2000/svg"
				>
					<title>Mobile menu</title>
					<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
				</svg>
				<svg
					class:hidden={!$menuIsOpen}
					xmlns="http://www.w3.org/2000/svg"
					class="block fill-current"
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

<!-- Hero -->
<section class="px-6 py-6 block w-full relative pt-5">
	<div class="h-full flex justify-center items-start">
		<!-- Grid -->
		<div class="hidden lg:grid grid-cols-12 grid-rows-2 h-full w-full">
			<!-- Image CTA -->
			<div
				class="z-10 rounded-2xl relative col-span-7 w-full aspect-video"
				style:background-image="url({data.page.hero.pictureURL1})"
				style:background-position="center"
				style:background-size="cover"
			>
				<a
					href={data.page.hero.btn1.link}
					type="button"
					class="absolute bottom-5 left-5 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
				>
					{data.page.hero.btn1.text}
				</a>
			</div>

			<!-- Logo Rounded -->
			<div class="hidden lg:flex col-span-5 -mt-28 justify-center items-center">
				<img class="w-40" src={data.page.hero.pictureURL2} alt="imaginé par forpro" />
			</div>

			<!-- Text -->
			<div
				class="hidden lg:flex text-2xl -mt-8 col-span-5 text-center text-primary justify-center items-center uppercase"
			>
				{@html data.page.hero.text}
			</div>

			<!-- Image CTA -->
			<div
				class="hidden lg:block -mt-16 rounded-2xl relative col-span-7 w-full aspect-video"
				style:background-image="url({data.page.hero.pictureURL3})"
				style:background-position="center"
				style:background-size="cover"
			>
				<a
					href={data.page.hero.btn2.link}
					type="button"
					class="absolute -top-3 right-10 rounded-full bg-secondary px-6 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
				>
					{data.page.hero.btn2.text}
				</a>
			</div>
		</div>

		<!-- Badge overlay !-->
		<img class="absolute z-40 h-[90%] -mt-6" src="/hero_overlay.svg" alt="overlay forpro" />
	</div>
</section>

<section class="relative mt-20 w-full">
	<!-- Background -->
	<img class="absolute z-10 -left-10 -top-10 lg:-left-10 lg:-top-10 lg:w-3/5" src="/bg_2_1.svg" />

	<!-- Container -->
	<div class="grid grid-cols-1 lg:grid-cols-12 h-full w-full">
		<!-- Title -->
		<div class="z-30 flex lg:col-span-12 text-6xl text-white uppercase justify-center items-center">
			<span>{data.page.food.title}</span>
		</div>

		<div class="-mt-8 mx-5 lg:mx-20 grid grid-cols-1 lg:grid-cols-2 col-span-full">
			<!-- Text -->
			<div class="flex flex-col z-20 pt-10 col-span-1 text-primary font-regular">
				<div class="prose-sm leading-4 mt-10 lg:pr-10">
					{@html data.page.food.text}
				</div>
				<div class="align-bottom h-full">
					<a
						href={data.page.food.btn.link}
						type="button"
						class="block mt-10 rounded-full w-max bg-white px-14 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					>
						{data.page.food.btn.text}
					</a>
				</div>
			</div>

			<!-- Image -->
			<div
				class="z-20 col-span-1 rounded-2xl aspect-square"
				style:background-image="url({data.page.hero.pictureURL1})"
				style:background-position="center"
				style:background-size="cover"
			></div>
		</div>
	</div>
</section>

<section class="relative mt-32 pb-20 bg-background w-full min-h-72">
	<!-- Container -->
	<div class="grid grid-cols-1 lg:grid-cols-12 h-full w-full">
		<!-- Title -->
		<div class="z-30 flex lg:col-span-12 text-6xl text-white uppercase justify-center items-center">
			<span>{data.page.lab.title}</span>
		</div>

		<div class="mx-5 lg:-mt-10 lg:mx-20 grid grid-cols-1 lg:grid-cols-2 lg:col-span-full">
			<!-- Image -->
			<div
				class="z-20 col-span-1 rounded-2xl aspect-square"
				style:background-image="url({data.page.lab.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>

			<!-- Text -->
			<div class="flex flex-col relative z-20 pt-10 col-span-1 text-primary font-regular">
				<div class="prose-sm leading-4 mt-10 lg:pl-10">
					{@html data.page.lab.text}
				</div>
				<div class="align-bottom h-full">
					<a
						href={data.page.lab.btn.link}
						type="button"
						class="block lg:ml-10 mt-10 w-max rounded-full bg-white px-5 lg:px-14 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					>
						{data.page.lab.btn.text}
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Background -->
	<img
		class="absolute z-10 -right-20 -bottom-32 lg:-right-52 lg:-bottom-52 lg:w-3/5"
		src="/bg_3_1.svg"
	/>
</section>

<section class="z-10 mt-10 relative w-full">
	<div
		class="min-h-96 rounded-3xl"
		style:background-image="url({data.page.highlight.pictureURL})"
		style:background-position="center"
		style:background-size="cover"
	></div>
</section>

<section class="pb-20 rounded-3xl bg-primary relative w-full">
	<!-- Container -->
	<div class="lg:grid lg:grid-cols-12 h-full w-full">
		<!-- Title -->
		<div
			class="z-30 lg:mt-20 pt-10 lg:pt-0 flex lg:col-span-12 text-3xl lg:text-6xl text-white text-center uppercase justify-center items-center"
		>
			<span>{@html data.page.formation.title}</span>
		</div>

		<div class="lg:-mt-8 mx-5 lg:mx-20 grid grid-cols-1 lg:grid-cols-2 lg:col-span-full">
			<!-- Text -->
			<div class="z-20 pt-12 text-center lg:text-left col-span-1 text-secondary font-regular">
				<div class="prose-sm leading-4 mt-2 lg:pr-10">
					<span>{@html data.page.formation.text}</span>
				</div>

				<a
					href={data.page.formation.btn.link}
					type="button"
					class="mt-10 rounded-full bg-secondary px-14 py-1 text-sm font-semibold text-primary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
				>
					<span>{data.page.formation.btn.text}</span>
				</a>
			</div>

			<!-- Image -->
			<div
				class="z-20 col-span-1 rounded-2xl"
				style:background-image="url({data.page.formation.pictureURL})"
				style:background-position="center"
				style:background-size="cover"
			></div>
		</div>
	</div>
</section>

<section class="px-5 lg:px-0 relative mt-20 pb-20 bg-background w-full">
	<!-- Container -->
	<div class="z-20 lg:grid lg:grid-cols-12 h-full w-full relative">
		<!-- Title -->
		<div
			class="z-30 flex mx-auto col-span-12 text-3xl lg:text-6xl text-primary text-center uppercase justify-center items-center"
		>
			<span>{data.page.univers.title}</span>
		</div>

		<!-- Description -->
		<div
			class="mt-10 z-30 flex mx-auto col-span-12 text-primary text-xs text-center uppercase justify-center items-center"
		>
			<p>{data.page.univers.subtitle}</p>
		</div>

		<div
			class="mt-10 z-30 flex mx-auto col-span-12 text-primary text-center justify-center items-center"
		>
			<span class="text-xl font-bold">{data.page.univers.blogTitle1}</span>
		</div>

		<!-- Image -->
		<div
			class="z-30 relative mt-5 rounded-3xl col-span-1 justify-center items-center lg:col-start-2 lg:col-span-10 min-h-0"
		>
			<img
				class="w-full h-auto rounded-3xl lg:w-full lg:h-full object-contain object-center"
				src={data.page.univers.blogPictureUrl1}
			/>
			<img
				class="hidden lg:block absolute w-40 left-0 top-0 -mt-14 -ml-10"
				src="/hero_1_2.svg"
				alt="imaginé par forpro"
			/>
		</div>

		<div
			class="mt-10 mx-auto grid grid-cols-1 col-span-1 lg:grid-cols-2 lg:col-start-2 lg:col-span-10 gap-5 text-primary"
		>
			{@html data.page.univers.blogText1}
		</div>
	</div>

	<!-- Background -->
	<img class="absolute z-10 -right-32 top-20 lg:-right-64 lg:top-0 w-[50em]" src="/bg_5_1.svg" />
</section>

<section class="px-5 lg:px-0 relative mt-20 pb-20 bg-background">
	<!-- Container -->
	<div class="relative lg:grid lg:grid-cols-12 z-20">
		<div
			class="mt-10 z-30 flex mx-auto col-span-12 text-primary text-center justify-center items-center"
		>
			<span class="text-xl font-bold">{data.page.univers.blogTitle1}</span>
		</div>

		<!-- Image -->
		<div
			class="z-30 relative mt-5 rounded-3xl col-span-1 justify-center items-center lg:col-start-2 lg:col-span-10 min-h-0"
		>
			<img
				class="w-full h-auto rounded-3xl lg:w-full lg:h-full object-contain object-center"
				src={data.page.univers.blogPictureUrl2}
			/>
			<img
				class="hidden lg:block absolute w-40 top-0 -right-10 rotate-45 -mt-14 -ml-10"
				src="/hero_1_2.svg"
				alt="imaginé par forpro"
			/>
		</div>

		<!-- Description -->
		<div
			class="mt-10 mx-auto grid grid-cols-1 col-span-1 lg:grid-cols-2 lg:col-start-2 lg:col-span-10 gap-5 text-primary"
		>
			{@html data.page.univers.blogText2}
		</div>
	</div>

	<!-- Background -->
	<img
		class="absolute z-10 left-[-6rem] top-[-0rem] lg:left-[-23rem] lg:top-[-5rem] lg:w-[175em]"
		src="/bg_5_2.svg"
	/>
</section>

<section class="pb-20 rounded-3xl bg-primary relative w-full">
	<!-- Container -->
	<div class="lg:px-36 grid h-full w-full">
		<!-- Title -->
		<div
			class="z-30 mt-20 text-3xl lg:text-6xl text-white text-center uppercase justify-center items-center"
		>
			<span>{data.page.values.title}</span>
		</div>

		<div class="-mt-8">
			<!-- Text -->
			<div class="z-20 pt-12 text-white text-center font-regular">
				<div class="mt-2 px-5 lg:pr-10">
					{@html data.page.values.text}
				</div>
			</div>
		</div>

		<div class="mt-10 grid grid-cols-1 lg:grid-cols-4 justify-around lg:col-span-full">
			{#each data.page.values.list as value}
				<div class="flex justify-center items-center">
					<div class="inline-grid justify-center w-full h-full text-center">
						<img src="/circle.svg" class="w-32" />
						<span class="uppercase text-white">{@html value.title}</span>
					</div>
				</div>
			{/each}
		</div>
	</div>
</section>

<section class="pt-10 pb-20 lg:px-20 rounded-3xl bg-secondary relative w-full">
	<div
		class="grid grid-cols-1 lg:grid-cols-4 lg:grid-rows-[1fr_auto] lg:grid-flow-row lg:auto-rows-min lg:gap-4"
	>
		<div class="flex justify-center items-center lg:justify-start lg:items-start col-span-1">
			<img class="h-[25em]" src="/footer_logo.svg" alt="overlay forpro" />
		</div>

		<div class="col-span-1 w-full">
			<div class="prose-sm leading-4 text-center lg:text-left text-primary w-full font-bold">
				{@html data.page.footer.text1}
			</div>
			<div class="justify-center lg:justify-normal mt-10 grid space-y-5">
				<a
					href={data.page.footer.btn1.link}
					type="button"
					class="rounded-full text-center w-36 bg-primary px-6 py-1 text-sm font-semibold text-secondary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
				>
					{data.page.footer.btn1.text}
				</a>
				<a
					href={data.page.footer.btn2.link}
					type="button"
					class="rounded-full text-center w-36 bg-primary px-6 py-1 text-sm font-semibold text-secondary shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
				>
					{data.page.footer.btn2.text}
				</a>
			</div>
		</div>

		<div class="mt-10 text-center lg:text-left col-span-1 lg:col-span-2 lg:row-start-2">
			<div class="prose-sm leading-4 text-primary font-bold">
				{@html data.page.footer.text2}
			</div>
		</div>

		<div
			class="mt-10 self-end p-10 lg:ml-16 h-fit relative lg:py-10 lg:align-bottom lg:col-span-2 lg:row-span-2 bg-background rounded-3xl"
		>
			<img
				class="hidden absolute -left-14 -top-12 -rotate-12 w-36 lg:block"
				src="/badge_footer.svg"
			/>
			<div class="prose-sm my-5 text-primary text-xs">
				{@html data.page.footer.text3}
			</div>
			<img src="/footer_logo_2.svg" class="w-44" />
		</div>
	</div>
</section>
