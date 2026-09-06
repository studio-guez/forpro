import type { RequestHandler } from './$types';
import { proxyRestaurantPdf } from '$lib/server/restaurantPdf';

// The PopUp Café menu uploaded in the Panel (restaurant content, "Carte du PopUp Café")
export const GET: RequestHandler = ({ fetch }) =>
	proxyRestaurantPdf(fetch, (page) => page.univers?.popupMenuUrl);
