import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';

export default defineConfig({
	plugins: [sveltekit()],
	server: {
		// Allow access through Traefik (e.g. http://restaurant.localhost) when
		// running inside docker compose.
		allowedHosts: ['restaurant.localhost', '.localhost']
	}
});
