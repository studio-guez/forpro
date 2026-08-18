import type { Page } from './page';
import type { FaqPage } from './faq';

// Any CMS content resolved by the [...slug] route, discriminated by `template`.
export type CmsContent = Page | FaqPage;
