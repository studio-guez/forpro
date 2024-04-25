<script lang="ts">
import {modaleIsOpen} from "../../store";

</script>

<div class="s-app-modal">
    <div class="s-app-modal__box" >
        <div class="s-app-modal__img-content">
        </div>
        <div class="s-app-modal__text-content">
            <h2 class="s-app-modal__text-content__title">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab atque, vitae.</h2>
            <div class="s-app-modal__text-content__content" >
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium delectus esse est eum, facere iure iusto laboriosam nam nemo optio</p>
                <p>Perferendis suscipit tenetur ullam! Error hic necessitatibus nemo porro quod.</p>
            </div>

            <div class="s-app-modal__text-content__cta" >
                <button class="s-app-modal__button s-app-modal__button--remove"
                        on:click={() => modaleIsOpen.set(false)}
                >fermer</button>
                <button class="s-app-modal__button s-app-modal__button--validate"
                        on:click={() => modaleIsOpen.set(false)}
                >voir -></button>
            </div>
        </div>
    </div>
</div>

<style lang="scss">
    .s-app-modal {
      display: block;
      container-type: inline-size;
      container-name: s-app-modal;
      width: min(100%, 40rem);
    }

    .s-app-modal__box {
      border: solid 3px var(--app-color--blue);
      overflow: hidden;
      background: white;
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      border-radius: 1rem;
    }

    .s-app-modal__img-content {
      width: calc(100% / 5 * 2);
      background: lightgray;
    }

    .s-app-modal__text-content {
      width: calc(100% / 5 * 3);
      padding: 3rem 1rem;

      p + p {
        margin-top: 1em;
      }
    }

    .s-app-modal__text-content__title {
      font-size: 1.25rem;
      line-height: 1em;
      margin-bottom: 1em;
    }

    .s-app-modal__text-content__cta {
      margin-top: 2rem;
      display: flex;
      justify-content: center;
      gap: 1rem;
    }

    .s-app-modal__button {
      padding: .85em 1em 1.15em;
      color: white;
      background: var(--app-color--blue);
      border-radius: 2em;
      line-height: 1ex;
    }

    .s-app-modal__button--remove {
      background: var(--app-color--blue--light);
      color: black;
    }

</style>
