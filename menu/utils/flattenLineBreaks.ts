// A line with only tags or no-break spaces (`</em>`, `&nbsp;`) gets no
// separator, or the menu would show a stray comma
const hasText = (html: string) => html.replace(/<[^>]*>|&nbsp;|\s/g, '') !== ''

// The FoodCourt displays are laid out for single-line dish texts, so the
// Panel writer's line breaks are turned into ", " instead of being rendered
export function flattenLineBreaks(html: unknown): string {
  return String(html ?? '')
    .split(/<br\s*\/?>/i)
    .map(line => line.trim())
    .reduce((flat, line) => (hasText(flat) && hasText(line) ? `${flat}, ${line}` : flat + line), '')
}
