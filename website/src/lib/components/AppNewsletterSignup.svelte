<div class="s-newsletter">
    <span class="s-newsletter__symbole"
    >@</span>
    <input class="s-newsletter__input"
           type="email" />
</div>

<style>
    .s-newsletter {
        background: var(--app-color--pink);
        color: white;
        padding: max(2rem, 4vw);
        display: flex;
        align-items: center;
        box-sizing: border-box;
        border-radius: max(1rem, 2vw);
        flex-wrap: nowrap;
        gap: 1rem;
        margin-top: 5rem;
    }

    .s-newsletter__symbole {
        font-weight: 800;
        line-height: 1em;
        font-size: max(4rem, 6vw);
        display: block;
    }

    .s-newsletter__input {
        display: block;
        border: none;
        border-radius: 1rem;
        line-height: 1rem;
        font-size: 1rem;
        padding: 1rem;
        width: 100%;
        color: black;
    }
</style>
