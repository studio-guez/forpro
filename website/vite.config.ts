import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
	plugins: [tailwindcss(), sveltekit()],
	server: {
		// Allow access through Traefik (e.g. http://website.localhost) when
		// running inside docker compose.
		allowedHosts: ['website.localhost', '.localhost']
	}
});
