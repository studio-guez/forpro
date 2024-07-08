<script lang="ts">
	import { menuIsOpen } from '../../store';
</script>

<nav
	class="absolute right-1 top-1 h-[99vh] w-1/2 p-10 z-50 width-1/2 height-1/2 rounded-2xl border-2 border-black border-solid bg-background"
	class:hidden={!$menuIsOpen}
	class:block={$menuIsOpen}
>
	<ul class="mt-10 flex flex-col space-y-4">
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
