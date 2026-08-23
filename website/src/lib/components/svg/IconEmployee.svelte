<script lang="ts">
	interface Props {
		class?: string;
		width?: number | string;
		height?: number | string;
	}

	let { class: className = '', width = 21, height = 21 }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" {width} {height} viewBox="0 0 21 21" fill="none" aria-hidden="true" class={className}>
  <path d="M14 1.75V3.5M15.6756 19.25C15.6756 17.8576 15.1225 16.5223 14.1379 15.5377C13.1534 14.5531 11.818 14 10.4256 14C9.03324 14 7.69788 14.5531 6.71331 15.5377C5.72875 16.5223 5.17562 17.8576 5.17562 19.25M7 1.75V3.5M14 10.5C14 12.433 12.433 14 10.5 14C8.567 14 7 12.433 7 10.5C7 8.567 8.567 7 10.5 7C12.433 7 14 8.567 14 10.5ZM4.375 3.5H16.625C17.5915 3.5 18.375 4.2835 18.375 5.25V17.5C18.375 18.4665 17.5915 19.25 16.625 19.25H4.375C3.4085 19.25 2.625 18.4665 2.625 17.5V5.25C2.625 4.2835 3.4085 3.5 4.375 3.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
