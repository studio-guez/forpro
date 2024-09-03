<script lang="ts" >
    import type {
        IAnimatedList, IBlockImage,
        IBlockListLogo, IBlockMap,
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
          | IBlockListLogo
  };

</script>

<div class="s-block-logos">
  <div class="flex flex-wrap justify-center gap-5">
    {#each data.image as image}
      <img class="object-contain filter grayscale hover:grayscale-0 transition ease-in-out duration-300"
           src="{image.resize.xxl}"
           alt=""
      />
    {/each}
  </div>
</div>

<style lang="scss">
  img {
    display: block;
    height: 4rem;
  }
</style>
