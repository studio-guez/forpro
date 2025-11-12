import { showCookieConsent } from './store';
import { browser } from '$app/environment';

// Vérification du consentement des cookies au chargement de l'application
if (browser) {
    const cookieConsent = localStorage.getItem('cookieConsent')
    if (cookieConsent === null || cookieConsent !== 'accepted') {
        showCookieConsent.set(true)
    }
}
