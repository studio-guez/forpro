<div class="s-newsletter"
     id="s-newsletter"
>
    <div class="s-newsletter__title">Je m’inscris à la newsletter de ForPro</div>

    <form class="app-flex app-flex--align_center app-flex--nowrap app-flex--gap_regular"
          method="post"
          action="https://newsletter.infomaniak.com/external/submit"
          target="_blank"
    >
        <span class="s-newsletter__symbole"
        >@</span>
        <input type="email"
               class="s-newsletter__input"
               name="inf[1]"
               data-inf-meta="1"
               data-inf-error="Merci de renseigner une adresse email"
               required="required"
               placeholder="Email"
               bind:value="{emailValue}"
        >
        <input type="hidden"
               name="key"
               value="eyJpdiI6IkxcL3F5NGVpMnZOSzNERm1XV0Jmek92Q3dHOVdvSERCaW5qcUFNVm5KOXRvPSIsInZhbHVlIjoiOFBTZG9GSUZhcEQzZFBvQ09tYUIyTzk5YTJ5MWc5blFkVjBXeDVTWmVFRT0iLCJtYWMiOiIzZTU3NDBkZGFkMTVkMjc1YzYxNWFmZTRhMmQxOGMwMjc4YjM4YWUyZGFhNTFlNjZlYjQ5ZTdjYmU0YjJjMDc1In0="
        />
        <input type="hidden"
               name="webform_id"
               value="12660"
        />
        {#if emailValue.length > 0}
            <div
                    class="s-newsletter__validate-box"
                    transition:fly={{
                        duration: 500,
                        y: 10,
                        opacity: 0
                    }}
            >
                <input type="submit"
                       value="Valider"
                       style="
                                --app-button--color: var(--app-color--green);
                                --app-button--background-color: var(--app-color--blue);
                            "
                       class="app-button app-button--rounded app-button--xl app-button--without-over-effect"
                />
            </div>
        {/if}
    </form>
</div>

<script lang="ts">
    import {fly} from "svelte/transition"

    let emailValue = ''
</script>



<style>
    .s-newsletter {
        background: var(--app-color--pink);
        color: white;
        padding: max(1vw, 1rem) var(--app-gutter_regular) max(4vw, 2rem);
        box-sizing: border-box;
        border-radius: max(1rem, 2vw);
        margin-top: 5rem;
        position: relative;
        margin-bottom: 3rem;
    }

    .s-newsletter__title {
        font-weight: 600;
        line-height: 1em;
        font-size: max(1rem, 3vw);
        text-align: center;
        margin-bottom: .5em;
    }

    .s-newsletter__symbole {
        font-weight: 800;
        line-height: 1em;
        font-size: 6vw;
        display: block;
    }

    .s-newsletter__input {
        display: block;
        border: none;
        border-radius: 4vw;
        line-height: 1em;
        font-size: 4vw;
        padding: .5vw 2vw 1.5vw;
        width: 100%;
        color: black;
    }

    .s-newsletter__validate-box {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%);
        transform-origin: top;
    }
</style>
