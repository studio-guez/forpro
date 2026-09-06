import type { RequestHandler } from './$types';
import { proxyRestaurantPdf } from '$lib/server/restaurantPdf';

// The FoodLab menu published from the Panel ("Le Lab" button)
export const GET: RequestHandler = ({ fetch }) =>
	proxyRestaurantPdf(fetch, (page) => page.lab?.btn?.link);
