export function formatDate(stringDate, removeHour = false) {
    const dateOptions = removeHour ?
        {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }
        : {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }

    const date = new Date(stringDate)

    return new Intl.DateTimeFormat('fr-FR', dateOptions).format(date)
}
