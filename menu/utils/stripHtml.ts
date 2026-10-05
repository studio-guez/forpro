import {flattenLineBreaks} from '~/utils/flattenLineBreaks'

// Plain text for JSON-LD, with line breaks flattened as on the menu displays.
// Script content is not entity-decoded, so entities are resolved here.
export function stripHtml(html?: string): string {
  const text = new DOMParser().parseFromString(flattenLineBreaks(html), 'text/html').body.textContent ?? ''
  return text.replace(/\s+/g, ' ').trim()
}
