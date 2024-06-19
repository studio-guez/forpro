<div class="s-app-cookie-consent"
>
  <div>
    Ce site utilise des cookies pour faciliter et améliorer votre expérience en ligne.
    Notre politique de confidentialité présente les principes et les applications en matière de collecte et de traitement des données lorsque vous utilisez notre site Internet.

    <a href="politique-de-confidentialite">Lire notre politique de confidentialité.</a>
  </div>

  <div class="s-app-cookie-consent__icon"
       on:click={closeCookieConsent}
  >
    <img
         src="/cancel_24dp_FILL0_wght400_GRAD0_opsz24.svg" alt="close cookie consent"
    >
  </div>

</div>

<script lang="ts">
  import {showCookieConsent} from "../../store";

  function closeCookieConsent() {
      showCookieConsent.set(false)
  }
</script>

<style lang="scss">
  .s-app-cookie-consent {
    background: var(--app-color--blue);
    color: white;
    font-size: .75rem;
    box-sizing: border-box;
    width: 100%;
    padding: .25rem .5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;

    @media (max-width: 570px) {
      flex-direction: column;
      padding-bottom: 1rem;
    }

    a {
      text-decoration: underline;
    }

    .s-app-cookie-consent__icon {
      flex-shrink: 0;
      cursor: pointer;

      img {
        display: block;
        height: 1rem;
      }
    }
  }
</style>
