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
    ],
]);
