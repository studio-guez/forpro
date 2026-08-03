<div class="s-app-cookie-consent"
>
  <div>
    Ce site utilise des cookies pour faciliter et améliorer votre expérience en ligne.
    Notre politique de confidentialité présente les principes et les applications en matière de collecte et de traitement des données lorsque vous utilisez notre site Internet.

    <a href="politique-de-confidentialite">Lire notre politique de confidentialité.</a>
  </div>

  <div class="s-app-cookie-consent__icon"
       on:click={closeCookieConsent}
       on:keyup={(e) => e.keyCode === 32 || e.keyCode === 13 && closeCookieConsent() }
       role="button"
       tabindex="0"
  >
    <svg style="fill: var(--app-color--blue)" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
  </div>

</div>

<script lang="ts">
  import {showCookieConsent} from "../../store";

  function closeCookieConsent() {
      localStorage.setItem('cookieConsent', 'accepted')

      showCookieConsent.set(false)
  }
</script>

