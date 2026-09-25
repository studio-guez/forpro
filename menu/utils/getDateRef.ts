export function getDateRef(getNextWeek: boolean) {
    const date = new Date()
    if( getNextWeek ) date.setDate(date.getDate() + 7)

    const dayOfWeek = date.getDay();

    const diffToFriday = dayOfWeek <= 5 ? 5 - dayOfWeek : 5 + (7 - dayOfWeek)

    const friday = new Date(date)
    friday.setDate(date.getDate() + diffToFriday)

    const monday = new Date(friday)
    monday.setDate(friday.getDate() - 4)

    return { monday, friday };
}
