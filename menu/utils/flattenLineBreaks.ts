// The FoodCourt displays are laid out for single-line dish texts, so the
// Panel writer's line breaks are turned into ", " instead of being rendered
export function flattenLineBreaks(html: unknown): string {
  return String(html ?? '')
    .split(/<br\s*\/?>/i)
    .map(part => part.trim())
    .filter(Boolean)
    .join(', ')
}
