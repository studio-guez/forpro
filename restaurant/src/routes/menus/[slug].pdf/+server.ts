import type { RequestHandler } from './$types';
import { proxyRestaurantPdf } from '$lib/server/restaurantPdf';

export const GET: RequestHandler = ({ fetch, params }) =>
	proxyRestaurantPdf(
		fetch,
		(page) => page.specialMenus?.find((menu) => menu.slug === params.slug)?.url
	);
