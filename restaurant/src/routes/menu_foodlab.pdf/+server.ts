import type { RequestHandler } from './$types';
import { proxyRestaurantPdf } from '$lib/server/restaurantPdf';

export const GET: RequestHandler = ({ fetch }) =>
	proxyRestaurantPdf(fetch, (page) => page.lab?.btn?.link);
