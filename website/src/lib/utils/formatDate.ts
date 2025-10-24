export function formatDate(stringDate: string, withYear = false, dayCapitalise = false): string {

    const startDate = new Date(stringDate)

    const options: Intl.DateTimeFormatOptions = {
        weekday: "long",
        month: 'long',
        day: 'numeric',
        year: withYear ? 'numeric' : undefined
    }
    const formatter = new Intl.DateTimeFormat('fr-FR', options)

  const dateToReturn = formatter.format(startDate).replace(/\b1\b/, '1<sup>er</sup>')

  if (dayCapitalise) {
		return dateToReturn.charAt(0).toUpperCase() + dateToReturn.slice(1)
	}

    return dateToReturn
}
