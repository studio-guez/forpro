/**
 * Session cookie (no `Expires`/`Max-Age`): the announcement banner stays closed until the
 * browser session ends. Read server-side so a dismissed banner is not rendered at all.
 */
export const BANNER_DISMISSED_COOKIE = 'forpro.bannerDismissed';

export const dismissBanner = (): void => {
	document.cookie = `${BANNER_DISMISSED_COOKIE}=1; Path=/; SameSite=Lax`;
};
