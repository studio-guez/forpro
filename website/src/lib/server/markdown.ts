/**
 * A page payload -> the Markdown an LLM reads at `<url>.md`.
 *
 * The CMS is the source of truth here too: this walks the very JSON the Svelte
 * templates render, so the two cannot describe different pages. It renders what
 * a reader needs — headings, prose, links, the facts a listing carries — and
 * drops what only matters on screen: layout variants, theme colours, image
 * crops, decorative shapes.
 */
import type { CmsContent } from '$lib/interfaces/content';
import type { Block, PageCta } from '$lib/interfaces/page';
import type { ContentBlock, ContentExternalLink } from '$lib/interfaces/eventProject';
import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

/** Blocks and sections are joined by a blank line, like paragraphs. */
const join = (parts: (string | null | undefined)[]): string =>
	parts.filter((part): part is string => Boolean(part && part.trim())).join('\n\n');

const heading = (level: number, text: string | null | undefined): string | null =>
	text && text.trim() ? `${'#'.repeat(level)} ${inline(text)}` : null;

/**
 * Kirby's writer field stores HTML. Its vocabulary is small and flat —
 * paragraphs, headings, lists, links and inline marks — so it is converted
 * here rather than pulling in a general HTML parser for a handful of tags.
 * Anything unexpected degrades to its text content instead of leaking markup.
 */
export function htmlToMarkdown(html: string | null | undefined, level = 3): string {
	if (!html) return '';

	let text = html.replace(/\r/g, '');

	// Lists first: their items must keep their inline marks, and the markers
	// have to survive the paragraph pass below.
	text = text.replace(
		/<ul[^>]*>([\s\S]*?)<\/ul>/gi,
		(_, body: string) =>
			listItems(body)
				.map((item) => `- ${item}`)
				.join('\n') + '\n\n'
	);
	text = text.replace(
		/<ol[^>]*>([\s\S]*?)<\/ol>/gi,
		(_, body: string) =>
			listItems(body)
				.map((item, index) => `${index + 1}. ${item}`)
				.join('\n') + '\n\n'
	);

	// Headings are pushed below the level of the section they sit in, so the
	// document keeps one descending outline.
	text = text.replace(/<h([1-6])[^>]*>([\s\S]*?)<\/h\1>/gi, (_, depth: string, body: string) => {
		const own = Math.min(level + Number(depth) - 1, 6);
		return `\n\n${'#'.repeat(own)} ${inline(body)}\n\n`;
	});

	text = text.replace(/<p[^>]*>([\s\S]*?)<\/p>/gi, (_, body: string) => `\n\n${inline(body)}\n\n`);
	text = text.replace(/<br\s*\/?>/gi, '  \n');

	return collapse(inline(text));
}

const listItems = (html: string): string[] =>
	[...html.matchAll(/<li[^>]*>([\s\S]*?)<\/li>/gi)]
		// A list item wraps its text in a paragraph; the bullet is the block here.
		.map((match) => inline(match[1].replace(/<\/?p[^>]*>/gi, ' ')))
		.filter(Boolean);

/** Inline marks to Markdown, every other tag to nothing. */
const inline = (html: string): string => {
	const text = html
		.replace(/<(strong|b)[^>]*>([\s\S]*?)<\/\1>/gi, (_, __, body: string) => `**${body.trim()}**`)
		.replace(/<(em|i)[^>]*>([\s\S]*?)<\/\1>/gi, (_, __, body: string) => `*${body.trim()}*`)
		.replace(/<code[^>]*>([\s\S]*?)<\/code>/gi, (_, body: string) => `\`${body.trim()}\``)
		.replace(
			/<a[^>]*href="([^"]*)"[^>]*>([\s\S]*?)<\/a>/gi,
			(_, href: string, body: string) => `[${body.trim()}](${href})`
		)
		.replace(/<[^>]+>/g, ' ');

	return decode(text)
		.replace(/[ \t\u00a0]+/g, ' ')
		.trim();
};

const decode = (text: string): string =>
	text
		.replace(/&nbsp;/g, ' ')
		.replace(/&amp;/g, '&')
		.replace(/&lt;/g, '<')
		.replace(/&gt;/g, '>')
		.replace(/&quot;/g, '"')
		.replace(/&#0?39;|&apos;|&rsquo;/g, '’')
		.replace(/&#(\d+);/g, (_, code: string) => String.fromCodePoint(Number(code)));

/** At most one blank line between blocks, none at the edges. */
const collapse = (text: string): string => text.replace(/\n{3,}/g, '\n\n').trim();

/** A rich-text field rendered under its own heading, when it has content. */
const section = (title: string, html: string | null | undefined, level = 2): string | null => {
	const body = htmlToMarkdown(html, level + 1);
	return body ? join([heading(level, title), body]) : null;
};

const bullet = (label: string, value: string | null | undefined): string | null =>
	value && String(value).trim() ? `- ${label} : ${inline(String(value))}` : null;

/**
 * A link from the payload, pointed at its Markdown twin when it is one of ours.
 *
 * A reader who arrived in Markdown should be able to stay there, and every one
 * of these files opens with the canonical HTML URL it stands for, so nothing is
 * lost on the way. Only site-internal page paths are rewritten: the CMS emits
 * those as paths (`/projets/x`, `/` for the home page) and everything else — a
 * `mailto:`, a `tel:`, a file on the CMS, an external site — as an absolute
 * URL, which is passed through untouched.
 */
const markdownUrl = (url: string, origin: string): string => {
	if (!url.startsWith('/') || url.startsWith('//')) return url;

	// `/.md` would be a dotfile path, which a reverse proxy may well refuse.
	return url === '/' ? `${origin}/index.md` : `${origin}${url}.md`;
};

/** A run of `- Label : value` lines as one block, or nothing when none apply. */
const facts = (lines: (string | null)[]): string | null => {
	const kept = lines.filter((line): line is string => Boolean(line));
	return kept.length ? kept.join('\n') : null;
};

const ctaLine = (cta: PageCta | null | undefined, origin: string): string | null =>
	cta && cta.url ? `- [${cta.label || cta.url}](${markdownUrl(cta.url, origin)})` : null;

const termNames = (terms: TaxonomyTerm[] | undefined): string | null =>
	terms && terms.length ? terms.map((term) => term.title).join(', ') : null;

/** The repeatable title + rich-text blocks events, projects and basic pages share. */
const contentBlocks = (blocks: ContentBlock[] | undefined, level = 2): string | null =>
	blocks && blocks.length
		? join(
				blocks.map((block) =>
					join([heading(level, block.title), htmlToMarkdown(block.description, level + 1)])
				)
			)
		: null;

const externalLinks = (links: ContentExternalLink[] | undefined, origin: string): string | null =>
	links && links.length
		? join([
				heading(2, 'Liens'),
				links.map((link) => `- [${link.title}](${markdownUrl(link.url, origin)})`).join('\n')
			])
		: null;

/**
 * The `body` blockbuilder. Each module is reduced to the text it carries: the
 * mirror of `Blocks.svelte`, which turns the same payload into components.
 */
function bodyBlocks(blocks: Block[] | undefined, origin: string): string | null {
	if (!blocks || blocks.length === 0) return null;

	return join(blocks.filter((block) => !block.isHidden).map((block) => renderBlock(block, origin)));
}

function renderBlock(block: Block, origin: string): string | null {
	// The payloads are template-shaped rather than typed per module here; the
	// interfaces live in `$lib/interfaces/page` and every field is optional in
	// practice, so each is read defensively.
	const c = block.content as Record<string, never> & Record<string, unknown>;
	const title = typeof c.title === 'string' && !c.hideTitle ? c.title : null;
	const head = heading(2, title);

	const collect = (items: unknown, render: (item: never) => string | null): string[] =>
		Array.isArray(items)
			? items.map((item) => render(item as never)).filter((line): line is string => Boolean(line))
			: [];

	/** Entries that are themselves blocks: separated, like paragraphs. */
	const list = (items: unknown, render: (item: never) => string | null): string | null =>
		join(collect(items, render));

	/** Entries that are single lines: one list, no blank lines inside it. */
	const bullets = (items: unknown, render: (item: never) => string | null): string | null => {
		const lines = collect(items, render);
		return lines.length ? lines.join('\n') : null;
	};

	switch (block.type) {
		case 'module-titre-texte-image':
			return join([
				head,
				htmlToMarkdown(c.description as string, 3),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-text':
			return join([
				head,
				htmlToMarkdown(c.content as string, 3),
				bullets(c.ctas, (cta: PageCta) => ctaLine(cta, origin))
			]);

		case 'module-cases':
			return join([
				head,
				htmlToMarkdown(c.intro as string, 3),
				list(c.rows, (row: { title: string | null; description: string; cta: PageCta | null }) =>
					join([
						heading(3, row.title),
						htmlToMarkdown(row.description, 4),
						ctaLine(row.cta, origin)
					])
				),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-grille-images':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				bullets(c.images, (item: { title: string }) => (item.title ? `- ${item.title}` : null)),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-video':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				heading(3, c.contentTitle as string),
				htmlToMarkdown(c.content as string, 4)
			]);

		case 'module-3-elements':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				list(c.elements, (item: { title: string; description: string }) =>
					join([heading(3, item.title), htmlToMarkdown(item.description, 4)])
				)
			]);

		case 'module-infos-pratiques':
			return join([
				head,
				htmlToMarkdown(c.subtitle as string, 3),
				list(c.elements, (item: { title: string; description: string }) =>
					join([heading(3, item.title), htmlToMarkdown(item.description, 4)])
				),
				list(c.faqs, (faq: { question: string; answer: string }) =>
					join([heading(3, faq.question), htmlToMarkdown(faq.answer, 4)])
				),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-timeline':
			return join([
				head,
				list(c.steps, (step: { title: string; shortDesc: string }) =>
					join([heading(3, step.title), htmlToMarkdown(step.shortDesc, 4)])
				)
			]);

		case 'module-agenda':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				bullets(
					c.events,
					(event: { title: string; url: string; dateStart: string | null }) =>
						`- [${event.title}](${markdownUrl(event.url, origin)})${event.dateStart ? ` — ${event.dateStart}` : ''}`
				),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-projets':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				bullets(
					c.projects,
					(project: { title: string; url: string }) =>
						`- [${project.title}](${markdownUrl(project.url, origin)})`
				),
				ctaLine(c.cta as PageCta, origin)
			]);

		case 'module-resources':
			return join([
				head,
				htmlToMarkdown(c.shortDesc as string, 3),
				list(c.resources, (resource: { title: string; shortDesc: string }) =>
					join([heading(3, resource.title), htmlToMarkdown(resource.shortDesc, 4)])
				)
			]);

		case 'module-cta':
			return join([
				head,
				htmlToMarkdown(c.subtitle as string, 3),
				bullets(c.links, (cta: PageCta) => ctaLine(cta, origin))
			]);

		case 'module-partenaires':
			return join([
				head,
				htmlToMarkdown(c.subtitle as string, 3),
				bullets(c.partners, (partner: { label: string | null; url: string | null }) => {
					if (!partner.label) return null;
					return partner.url ? `- [${partner.label}](${partner.url})` : `- ${partner.label}`;
				})
			]);

		default:
			return head;
	}
}

/**
 * The whole document: a title, where it came from, then the page itself.
 *
 * The source line is not decoration — a reader quoting this file needs the
 * canonical HTML URL to cite, and `.md` is a representation, not the page.
 */
export function renderPageMarkdown(page: CmsContent, origin: string): string {
	const canonical = page.seo.canonicalUrl || `${origin}/${page.path}`;

	const header = join([
		`# ${inline(page.title)}`,
		`Source : ${canonical}`,
		page.seo.description ? `> ${inline(page.seo.description)}` : null
	]);

	return `${join([header, renderTemplate(page, origin)])}\n`;
}

function renderTemplate(page: CmsContent, origin: string): string {
	switch (page.template) {
		case 'basic-page':
			return join([contentBlocks(page.blocks)]);

		case 'faq':
			return join([
				join(
					page.sections.map((faqSection) =>
						join([
							heading(2, faqSection.title),
							join(
								faqSection.faqs.map((faq) =>
									join([heading(3, faq.question), htmlToMarkdown(faq.answer, 4)])
								)
							)
						])
					)
				),
				bodyBlocks(page.body, origin)
			]);

		case 'event':
			return join([
				heading(2, page.subtitle),
				htmlToMarkdown(page.shortDesc),
				facts([
					bullet('Début', page.dateStart),
					bullet('Fin', page.dateEnd),
					bullet('Horaire', [page.timeStart, page.timeEnd].filter(Boolean).join(' – ')),
					bullet('Lieu', page.location),
					bullet('Programmes', termNames(page.programs)),
					bullet('Publics', termNames(page.publics))
				]),
				contentBlocks(page.blocks),
				externalLinks(page.externalLinks, origin)
			]);

		case 'project':
			return join([
				heading(2, page.subtitle),
				htmlToMarkdown(page.shortDesc),
				facts([
					bullet('Collectif', page.collectiveName),
					bullet('Membres', page.collectiveMembers.map((member) => member.name).join(', ')),
					bullet('Programmes', termNames(page.programs)),
					bullet('Catégories', termNames(page.categories))
				]),
				contentBlocks(page.blocks),
				externalLinks(page.externalLinks, origin)
			]);

		case 'job-offer':
			return join([
				facts([
					bullet('Lieu de travail', page.location),
					bullet(
						'Taux d’activité',
						page.activityRateMax
							? `${page.activityRateMin}% – ${page.activityRateMax}%`
							: `${page.activityRateMin}%`
					),
					bullet('Entrée en fonction', page.startDate),
					bullet('Délai de candidature', page.deadline),
					bullet('Secteurs', termNames(page.sectors)),
					bullet(
						'Candidatures',
						page.openToApplications ? (page.applicationEmail ?? 'ouvertes') : 'closes'
					)
				]),
				section('Description', page.description),
				section('Profil recherché', page.profile),
				section('Conditions', page.conditions),
				section('Contenu du dossier', page.applicationContent),
				page.applicationQuestions.length
					? join([
							heading(2, 'Questions du dossier'),
							join(
								page.applicationQuestions.map((question) =>
									join([heading(3, question.question), htmlToMarkdown(question.answer, 4)])
								)
							)
						])
					: null,
				page.recruitingSteps.length
					? join([
							heading(2, 'Étapes du recrutement'),
							join(
								page.recruitingSteps.map((step) =>
									join([heading(3, step.title), htmlToMarkdown(step.shortDesc, 4)])
								)
							)
						])
					: null
			]);

		case 'mission':
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.shortDesc),
				facts([
					bullet('Annonceur', page.announcer),
					bullet('Date', page.date),
					bullet('Lieu', page.location),
					bullet('Catégories', termNames(page.categories)),
					bullet('Candidatures', page.openToApplications ? 'ouvertes' : 'closes')
				]),
				section('Profil recherché', page.profile),
				section('Tâches', page.tasks),
				section('Planning', page.planning),
				ctaLine(page.applyCta, origin)
			]);

		case 'team':
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.intro),
				join(
					page.sections.map((teamSection) =>
						join([
							heading(2, teamSection.title),
							teamSection.members
								.map((member) => {
									const name = member.linkedin
										? `[${member.name}](${member.linkedin})`
										: member.name;
									return `- ${name}${member.role ? ` — ${member.role}` : ''}`;
								})
								.join('\n')
						])
					)
				),
				bodyBlocks(page.body, origin)
			]);

		case 'impressum':
			return join([
				page.partners.length
					? join([
							heading(2, page.partnersTitle ?? 'Partenaires'),
							page.partners
								.map((partner) =>
									partner.link
										? `- [${partner.title}](${markdownUrl(partner.link.url, origin)})`
										: `- ${partner.title}`
								)
								.join('\n')
						])
					: null,
				join(
					page.sections.map((creditSection) =>
						join([
							heading(2, creditSection.title),
							creditSection.credits
								.map((credit) => `- ${credit.role} : ${credit.names.join(', ')}`)
								.join('\n')
						])
					)
				),
				bodyBlocks(page.body, origin)
			]);

		case 'press':
			return join([
				page.contactPersons.length
					? join([
							heading(2, page.contactTitle ?? 'Contacts'),
							page.contactPersons
								.map(
									(contact) =>
										`- ${[contact.name, contact.role, contact.email, contact.phone]
											.filter(Boolean)
											.join(' — ')}`
								)
								.join('\n')
						])
					: null,
				page.resources
					? join([
							heading(2, page.resourcesTitle ?? 'Ressources'),
							`- [${page.resources.filename}](${page.resources.url}) (${page.resources.extension.toUpperCase()}, ${page.resources.size})`
						])
					: null
			]);

		// The index pages: their whole point is the list they carry, so the
		// Markdown carries it too — this is the page a reader is sent to when
		// llms.txt declines to enumerate a collection.
		case 'events':
			return join([
				page.upcomingEvents.length
					? join([
							heading(2, 'Événements à venir'),
							page.upcomingEvents
								.map(
									(event) =>
										`- [${event.title}](${markdownUrl(event.url, origin)})${event.dateStart ? ` — ${event.dateStart}` : ''}`
								)
								.join('\n')
						])
					: null,
				page.pastEvents.items.length
					? join([
							heading(2, 'Événements passés'),
							page.pastEvents.items
								.map(
									(event) =>
										`- [${event.title}](${markdownUrl(event.url, origin)})${event.dateStart ? ` — ${event.dateStart}` : ''}`
								)
								.join('\n')
						])
					: null,
				bodyBlocks(page.body, origin)
			]);

		case 'projects':
			return join([
				heading(2, 'Projets'),
				page.projects.items
					.map((project) => `- [${project.title}](${markdownUrl(project.url, origin)})`)
					.join('\n'),
				bodyBlocks(page.body, origin)
			]);

		case 'job-offers':
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.intro),
				page.jobOffers.length
					? join([
							heading(2, 'Offres ouvertes'),
							page.jobOffers
								.map(
									(offer) =>
										`- [${offer.title}](${markdownUrl(offer.url, origin)}) — ${offer.location}, délai ${offer.deadline}`
								)
								.join('\n')
						])
					: null,
				bodyBlocks(page.body, origin)
			]);

		case 'missions':
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.intro),
				page.missions.items
					.map(
						(mission) =>
							`- [${mission.title}](${markdownUrl(mission.url, origin)}) — ${mission.location}`
					)
					.join('\n'),
				bodyBlocks(page.body, origin)
			]);

		case 'factory-lab':
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.intro),
				page.companiesModule.companies.length
					? join([
							heading(2, page.companiesModule.title),
							htmlToMarkdown(page.companiesModule.intro, 3),
							page.companiesModule.companies
								.map((company) =>
									company.url
										? `- [${company.title}](${markdownUrl(company.url, origin)})`
										: `- ${company.title}`
								)
								.join('\n')
						])
					: null,
				bodyBlocks(page.body, origin)
			]);

		// `page` and anything added later: the shared header plus the body.
		default:
			return join([
				heading(2, page.introTitle),
				htmlToMarkdown(page.intro),
				ctaLine(page.introCta, origin),
				bodyBlocks(page.body, origin)
			]);
	}
}
