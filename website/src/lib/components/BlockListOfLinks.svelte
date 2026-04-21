<script lang="ts">

    import type {IBlockLinkList, IBlockLinkListContent} from "$lib/interfaces/cmsApiResponse";

  export let blockListOfLinksData: IBlockLinkListContent

</script>

<section class="block-list-of-links">
  <h4>{blockListOfLinksData.content.title}</h4>

  <div class="block-list-of-links__box">
    {#each blockListOfLinksData.content.listoflinks as linkContent}
      <a class="block-list-of-links__item"
         style="--color-button: {linkContent.color}"
         href="{linkContent.lien}"
         target="_blank"
      >
        <div>
          {linkContent.title}
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
          <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
        </svg>
      </a>
    {/each}
  </div>
</section>

<style lang="scss">
  .block-list-of-links {
    width: min(70rem, 100%);
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }

  .block-list-of-links__box {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .block-list-of-links__item {
    width: 100%;
    box-sizing: border-box;
    padding: 1rem;
    border: solid 2px currentColor;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--color-button);
    color: white;
    transition: all 150ms ease-in-out;

    svg {
      transition: transform 150ms ease-in-out;
      display: block;
      position: relative;
    }

    &:hover {
      color: var(--color-button);
      background: white;

      svg {
        transform: scale(1.25) rotate(15deg);
      }
    }
  }
</style>
