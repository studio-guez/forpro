export function formatTime(time: string): string {
    const parts = time.split(':')
    const hours = parts[0]
    const minutes = parts[1]

    if (minutes === '00') {
        return `${hours}h`
    } else {
        return `${hours}h${minutes}`
    }
}
