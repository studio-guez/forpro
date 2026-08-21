<?php

Kirby::plugin('forpro/parent-page', [
    'pageMethods' => [
        /**
         * Pages selectable as parent: excludes the page itself and any page
         * whose parentPage chain leads back to it (would create a cycle).
         */
        'possibleParents' => function () {
            $self = $this;

            return $this->site()
                ->index(true)
                ->filterBy('intendedTemplate', 'page')
                ->not($this->site()->homePage())
                ->filter(function ($candidate) use ($self) {
                    $current = $candidate;
                    $visited = [];

                    while ($current !== null) {
                        if ($current->is($self)) {
                            return false;
                        }
                        // guard against pre-existing cycles in content
                        if (in_array($current->id(), $visited, true)) {
                            return false;
                        }
                        $visited[] = $current->id();
                        $current = $current->parentPage()->toPage();
                    }

                    return true;
                });
        },

        /**
         * The parentPage chain from root to this page (cycle-safe),
         * e.g. [Grandparent, Parent, This page].
         */
        'parentChain' => function (): array {
            $chain = [$this];
            $visited = [$this->id()];
            $current = $this->parentPage()->toPage();

            while ($current !== null && in_array($current->id(), $visited, true) === false) {
                array_unshift($chain, $current);
                $visited[] = $current->id();
                $current = $current->parentPage()->toPage();
            }

            return $chain;
        },

        /**
         * Virtual URL path: real Kirby ancestors first (built-in parent/child,
         * used by the events/projects children), then the parentPage chain,
         * e.g. "events/my-event" or "grandparent/parent/slug".
         * Top-level containers (pages, events, ... at the content root) are
         * structural only and never appear in the URL.
         */
        'virtualPath' => function (): string {
            $ancestors = $this->parents()->flip()
                ->filter(fn($p) => $p->parent() !== null)
                ->values(fn($p) => $p->slug());

            $chain = array_map(fn($p) => $p->slug(), $this->parentChain());

            return implode('/', array_merge($ancestors, $chain));
        },

        /**
         * Title prefixed with the parentPage chain: "Grandparent > Parent > Title".
         */
        'breadcrumbTitle' => function (): string {
            return implode(' » ', array_map(
                fn($p) => $p->title()->value(),
                $this->parentChain()
            ));
        },

        // Lowercase alias for blueprint `sortBy`, which lowercases field names
        // before resolving them (camelCase methods wouldn't be found).
        'breadcrumbtitle' => function (): string {
            return $this->breadcrumbTitle();
        },

        /**
         * Public URL on the decoupled frontend. The panel's open/preview links
         * use this so they point at the frontend instead of the Kirby domain.
         * Home lives at the root; every other page uses its virtualPath.
         */
        'frontendUrl' => function (): string {
            $base = rtrim(option('url_frontend', $this->site()->url()), '/');

            if ($this->isHomePage() === true) {
                return $base . '/';
            }

            return $base . '/' . $this->virtualPath();
        },
    ],
]);
