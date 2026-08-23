<?php

/**
 * URL / label resolution for the shared link, CTA and external-link structures.
 */
trait UtilsLinks
{
    /**
     * Resolves the URL from a structure item using the type/page/url pattern.
     */
    static function resolvePageOrUrlItem(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            if ($linkedPage) {
                return $linkedPage->isHomePage() ? '/' : '/' . $linkedPage->virtualPath();
            }
            return null;
        }
        if ($item->type()->value() === 'mailto') {
            return $item->email()->isNotEmpty() ? 'mailto:' . $item->email()->value() : null;
        }
        if ($item->type()->value() === 'tel') {
            // "+41 (0) 21 ..." — the parenthesised trunk prefix is only for national
            // dialling and must be dropped, not just stripped of its parentheses.
            // Only after a country code: without one the 0 is part of the number.
            $raw   = preg_replace('/(\+\s*\d[\d\s.-]*)\(\s*0\s*\)/', '$1', (string)$item->phone()->value());
            $phone = preg_replace('/[^0-9+]/', '', $raw);
            // tel: URIs allow a single leading "+" only
            $phone = preg_replace('/(?<!^)\+/', '', $phone);
            return preg_match('/\d/', $phone) === 1 ? 'tel:' . $phone : null;
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves the label from a structure item, falling back to the page title or URL.
     */
    static function resolvePageOrUrlLabel(\Kirby\Cms\StructureObject $item): ?string
    {
        if ($item->label()->isNotEmpty()) {
            return $item->label()->value();
        }
        if ($item->type()->value() === 'page') {
            $linkedPage = $item->page()->toPage();
            return $linkedPage?->title()->value();
        }
        if ($item->type()->value() === 'mailto') {
            return $item->email()->isNotEmpty() ? $item->email()->value() : null;
        }
        if ($item->type()->value() === 'tel') {
            return $item->phone()->isNotEmpty() ? $item->phone()->value() : null;
        }
        return $item->url()->isNotEmpty() ? $item->url()->value() : null;
    }

    /**
     * Resolves a single CTA structure item, or null when it has no resolvable URL or label.
     */
    private static function resolveCtaItem(\Kirby\Cms\StructureObject $item): ?array
    {
        $url   = self::resolvePageOrUrlItem($item);
        $label = self::resolvePageOrUrlLabel($item);
        if (!$url || !$label) return null;
        return [
            'label' => $label,
            'url'   => $url,
            'icon'  => $item->icon()->isNotEmpty() ? $item->icon()->value() : null,
        ];
    }

    /**
     * Resolves a CTA structure field (max 1) to an array for JSON output, or null if empty.
     */
    static function resolveCtaStructure(\Kirby\Content\Field $field): ?array
    {
        return self::resolveCtaStructures($field)[0] ?? null;
    }

    /**
     * Resolves a multi-entry CTA structure field to a list, dropping unresolvable entries.
     */
    static function resolveCtaStructures(\Kirby\Content\Field $field): array
    {
        $ctas = [];
        foreach ($field->toStructure() as $item) {
            if ($cta = self::resolveCtaItem($item)) {
                $ctas[] = $cta;
            }
        }
        return $ctas;
    }

    /**
     * Resolves an external links structure (`title` + `link`) to a JSON-ready
     * list of `{title, url}` items, skipping entries without a URL.
     */
    static function getExternalLinks(\Kirby\Content\Field $field): array
    {
        $links = [];
        foreach ($field->toStructure() as $item) {
            if ($item->link()->isEmpty()) continue;
            $links[] = [
                'title' => $item->title()->value(),
                'url'   => $item->link()->value(),
            ];
        }
        return $links;
    }
}
