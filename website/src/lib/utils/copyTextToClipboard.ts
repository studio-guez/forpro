export function copyTextToClipboard(text: string) {
    // Création d'un élément temporaire pour y placer le texte à copier
    const tempInput = document.createElement("textarea")
    tempInput.value = text

    // Ajout de l'élément hors de la vue
    tempInput.style.position = "absolute"
    tempInput.style.left = "-9999px"
    document.body.appendChild(tempInput)

    // Sélection et copie du texte dans le presse-papiers
    tempInput.select()
    document.execCommand("copy")

    // Suppression de l'élément temporaire
    document.body.removeChild(tempInput)
}
