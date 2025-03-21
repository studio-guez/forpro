export function scrollToBlockByIndex(blocIndexInfo: "" | number | undefined) {

    if(typeof blocIndexInfo !== 'number') return

    console.log( blocIndexInfo )

    const elementToScroll = document?.querySelector(`.s-page__content > *:nth-child(${blocIndexInfo})`)

    if( !elementToScroll ) return

    elementToScroll.scrollIntoView({
        behavior: "smooth",
        block: 'start',
    })

}
