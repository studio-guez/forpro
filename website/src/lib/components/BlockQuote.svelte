<script lang="ts">
    import type {IQuote} from "$lib/interfaces/cmsApiResponse";

    export let content: IQuote;
</script>

<div class="s-block-quote">
    <svg
            class="s-text-quote__img-1"
            version="1.1"
            viewBox="0 0 322.53731 333.33332"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg">
        <defs
                id="defs2922" />
        <g
                id="g2926"
                transform="matrix(1.3333333,0,0,-1.3333333,-662.44612,1156.1532)">
            <g
                    id="g2944"
                    transform="translate(644.7006,867.1149)">
                <path
                        d="M 0,0 -6.302,-98.476 -53.829,0 l 21.287,-104.823 -69.784,80.065 48.741,-97.855 -82.301,53.242 70.625,-78.947 -82.605,23.318 82.605,-51.849 -70.625,-3.782 82.301,-21.921 -48.741,-22.689 69.784,4.901 L -53.829,-250 -6.302,-226.689 0,-250 l 19.938,29.66 28.561,-4.901 -7.52,22.689 41.081,21.921 -29.404,3.782 41.381,51.849 -41.381,-23.318 29.404,78.947 -41.081,-53.242 7.52,97.855 -28.561,-80.065 z"
                        style="fill-opacity:1;fill-rule:nonzero;stroke:none"
                        id="path2946" />
            </g>
        </g>
    </svg>
    <svg
            class="s-text-quote__img-2"
            viewBox="0 0 238.78518 333.33308"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg">
        <defs
                id="defs2922">
            <clipPath
                    clipPathUnits="userSpaceOnUse"
                    id="clipPath2954">
                <path
                        d="m 623.753,95.674 h 179.089 v 250 H 623.753 Z"
                        id="path2952" />
            </clipPath>
        </defs>
        <g
                id="g2926"
                transform="matrix(1.3333333,0,0,-1.3333333,-831.67078,460.89839)">
            <g
                    id="g2948">
                <g
                        id="g2950"
                        clip-path="url(#clipPath2954)">
                    <g
                            id="g2956"
                            transform="translate(730.3931,345.6738)">
                        <path
                                d="m 0,0 h -34.19 c -40.014,0 -72.45,-31.619 -72.45,-70.621 v -108.757 c 0,-39.003 32.436,-70.622 72.45,-70.622 H 0 c 40.013,0 72.451,31.619 72.451,70.622 V -70.621 C 72.451,-31.619 40.013,0 0,0"
                                style="fill-opacity:1;fill-rule:nonzero;stroke:none"
                                id="path2958" />
                    </g>
                </g>
            </g>
        </g>
    </svg>
    <svg
            class="s-text-quote__img-3"
            viewBox="0 0 218.79492 333.33201"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg">
        <defs
                id="defs2922">
            <clipPath
                    clipPathUnits="userSpaceOnUse"
                    id="clipPath2978">
                <path
                        d="M 390.6,95.773 H 554.697 V 345.772 H 390.6 Z"
                        id="path2976" />
            </clipPath>
        </defs>
        <g
                id="g2926"
                transform="matrix(1.3333333,0,0,-1.3333333,-520.79999,461.02932)">
            <g
                    id="g2972">
                <g
                        id="g2974"
                        clip-path="url(#clipPath2978)">
                    <g
                            id="g2980"
                            transform="translate(484.5632,345.772)">
                        <path
                                d="m 0,0 c 1.259,-6.199 1.923,-12.615 1.923,-19.187 0,-52.957 -42.929,-95.884 -95.887,-95.884 V -250 H 70.133 V 0 Z"
                                style="fill-opacity:1;fill-rule:nonzero;stroke:none"
                                id="path2982" />
                    </g>
                </g>
            </g>
        </g>
    </svg>

    <p class="s-text-quote__text"
    >{content.content.text}</p>
</div>

<style lang="scss" >
  @use "../../style/_scss-params";

  .s-block-quote {
    position: relative;
    container-type: inline-size;
    padding-top: 2rem;
    padding-bottom: 2rem;
    perspective: 999px;
    transform-style: preserve-3d;

    @media (min-width: scss-params.$fp-breakpoint-sm) {
      padding-top: 1rem;
      padding-bottom: 4rem;
    }
  }

  .s-text-quote__img-1 {
    position: absolute;
    top: 5vw;
    left: 9vw;
    fill: var(--app-color--pink);
    width: 25vw;
    transform-origin: center center;
    transform: translate3d(-50%, -50%, 0px) rotate(-25deg);

    * {
      fill: var(--app-color--pink) !important;
    }

    @media (min-width: scss-params.$fp-breakpoint-xs) {
      width: 13.5vw;
    }
  }

  .s-text-quote__img-2 {
    position: absolute;
    bottom: 5vw;
    left: 22vw;
    fill: var(--app-color--blue);
    width: 18vw;
    transform: translate(-50%, 50%) rotate(55deg);

    * {
      fill: var(--app-color--blue) !important;
    }

    @media (min-width: scss-params.$fp-breakpoint-xs) {
      width: 10vw;
    }
  }

  .s-text-quote__img-3 {
    position: absolute;
    top: 10vw;
    right: 15vw;
    fill: var(--app-color--pink);
    width: 18vw;
    transform: translate(50%, -50%) rotate(-35deg);

    * {
      fill: var(--app-color--pink) !important;
    }

    @media (min-width: scss-params.$fp-breakpoint-xs) {
      width: 13.5vw;
    }
  }


  .s-text-quote__text {
    margin: auto;
    text-align: center;
    position: relative;
    z-index: 5;
    font-weight: 700;
    max-width: 12em;
    line-height: 1em;
    font-size: 7.2vw;
    padding-bottom: 1em;
  }

</style>
