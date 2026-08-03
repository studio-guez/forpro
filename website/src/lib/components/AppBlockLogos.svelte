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
  <div class="flex flex-wrap justify-center s-block-logos__wrapper">
    {#each data.image as image}
      {#if image.link}
        <a href="{image.link}" target="_blank">
          <img class="object-contain"
               src="{image.resize.xxl}"
               alt=""
          />
        </a>
      {:else}
      <img class="object-contain"
           src="{image.resize.xxl}"
           alt=""
      />
      {/if}
    {/each}
  </div>
</div>

