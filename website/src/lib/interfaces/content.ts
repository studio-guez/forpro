import type { Page } from './page';
import type { FaqPage } from './faq';
import type { EventPage } from './event';
import type { ProjectPage } from './project';

// Any CMS content resolved by the [...slug] route, discriminated by `template`.
export type CmsContent = Page | FaqPage | EventPage | ProjectPage;
