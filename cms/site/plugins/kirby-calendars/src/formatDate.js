/**
 * @param stringDate {string | {date: string}}
 * @param removeHour {boolean | undefined}
 * @returns {string | 'NULL'}
 */
export function formatDate(stringDate, removeHour = false) {

    if (stringDate === undefined) return 'undefined'

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

    const date = (stringDate.hasOwnProperty('date')) ? new Date(stringDate.date) : new Date(stringDate)

    try {
        return new Intl.DateTimeFormat('fr-FR', dateOptions).format(date)
    } catch {
        return JSON.stringify(stringDate)
    }
}
