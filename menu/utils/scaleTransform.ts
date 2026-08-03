export function scaleTransform({elementToScale, elementForSize}: {
    elementToScale: HTMLElement,
    elementForSize: HTMLElement
}) {
    //prevent scale transformation
    elementToScale.style.transform = ""

    const sizeReferencePadding = 5;

    const sizeReference = {
        width:  window.innerWidth   - sizeReferencePadding * 2,
        height: window.innerHeight  - sizeReferencePadding * 2,
    }
    const elementSizes = elementForSize.getBoundingClientRect()

    if(window.innerWidth / window.innerHeight > elementSizes.width / elementSizes.height) {
        elementToScale.style.transform = `scale(${sizeReference.height / elementSizes.height})`
    } else {
        elementToScale.style.transform = `scale(${sizeReference.width / elementSizes.width})`
    }
}
