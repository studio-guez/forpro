<script lang="ts">
	import { menuIsOpen } from '../../store';
</script>

<nav
	class="lg:width-1/2 height-1/2 absolute right-1 top-1 z-50 flex h-[98vh] w-[96vw] rounded-2xl border-2 border-solid border-black bg-background p-5 lg:h-[99vh] lg:w-1/2 lg:p-10"
	class:hidden={!$menuIsOpen}
	class:lg:block={$menuIsOpen}
>
	<ul class="flex flex-col space-y-4 self-center text-center lg:mt-10 lg:text-left">
		<li class="text-3xl font-bold hover:text-secondary">
			<a
				on:click={() => {
					$menuIsOpen = false;
				}}
				href="#lefood">Le Food</a
			>
		</li>
		<li class="text-3xl font-bold hover:text-secondary">
			<a
				on:click={() => {
					$menuIsOpen = false;
				}}
				href="#lelab">Le Lab</a
			>
		</li>
		<li class="text-3xl font-bold hover:text-secondary">
			<a
				on:click={() => {
					$menuIsOpen = false;
				}}
				href="#equipe-formation">Équipe & Formation</a
			>
		</li>
		<li class="text-3xl font-bold hover:text-secondary">
			<a
				on:click={() => {
					$menuIsOpen = false;
				}}
				href="#foodcourt-popup-cafe">FoodCourt & Popup Café</a
			>
		</li>
		<li class="text-3xl font-bold hover:text-secondary">
			<a
				on:click={() => {
					$menuIsOpen = false;
				}}
				href="#engagements">Engagements</a
			>
		</li>
	</ul>
</nav>
