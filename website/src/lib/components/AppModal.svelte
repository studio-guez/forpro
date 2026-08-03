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

