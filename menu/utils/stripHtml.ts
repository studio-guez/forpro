// Plain text for JSON-LD: script content is not entity-decoded, so entities
// are resolved here, and line breaks become ", " as on the menu displays
export function stripHtml(html?: string): string {
  const body = new DOMParser().parseFromString(String(html ?? ''), 'text/html').body
  body.querySelectorAll('br').forEach(br => br.replaceWith('\n'))

  return (body.textContent ?? '')
    .split('\n')
    .map(line => line.replace(/\s+/g, ' ').trim())
    .filter(Boolean)
    .join(', ')
}
