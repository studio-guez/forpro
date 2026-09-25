export function isEvenWeek(date: Date): boolean {
    const startOfYear = new Date(date.getFullYear(), 0, 1);
    const dayOfYear = Math.floor((date.getTime() - startOfYear.getTime()) / (1000 * 60 * 60 * 24)) + 1;

    const weekNumber = Math.ceil((dayOfYear + startOfYear.getDay() - 1) / 7);

    return weekNumber % 2 === 0;
}
