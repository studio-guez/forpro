<script lang="ts" >
    import type {
        IAnimatedList, IBlockImage,
        IBlockListLogo, IBlockMap, IBlockVideo,
        ICapsules,
        ICards, ICardsFocus,
        ICta, IDropdown, IGoogleMaps, IHtmlContent,
        IImage, IList,
        IProfiles,
        IQuote
    } from "$lib/interfaces/cmsApiResponse";

  export let data: {
      image: IImage[]
      content: ICta
          | IQuote
          | ICapsules
          | ICards
          | IProfiles
          | IList
          | IDropdown
          | ICardsFocus
          | IHtmlContent
          | IBlockMap
          | IAnimatedList
          | IGoogleMaps
          | IBlockImage
          | IBlockVideo
          | IBlockListLogo
  };

</script>

<div class="s-block-logos">
  <div class="flex flex-wrap justify-start s-block-logos__wrapper">
    {#each data.image as image}
      <img class="object-contain filter grayscale hover:grayscale-0 transition ease-in-out duration-300"
           src="{image.resize.xxl}"
           alt=""
      />
    {/each}
  </div>
</div>

<style lang="scss">
  .s-block-logos {
    width: min(70rem, 100%);
  }

  img {
    display: block;
    height: 5rem;
    max-width: 75vw;
  }

  .s-block-logos__wrapper {
    gap: 3rem 5rem;
  }
</style>
