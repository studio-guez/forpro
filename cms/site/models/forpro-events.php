<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;


enum FilterType: string {
    case upcoming = 'upcoming';
    case past = 'past';
    case all = 'all';
}

class ForproEventsPage extends Page {
    public function getUpcomingEvents() {

        $today = new DateTime('today');

        $listedEvent = $this->children()->listed();

        return $listedEvent->filter(function ($item) use ($today) {
            $dateToCompare =
                $item->dateEnd()->value() ?
                    new DateTime($item->dateEnd()->value())
                    : new DateTime($item->datestart()->value());

            return $dateToCompare >= $today;
        });

    }

    public function getQueryFilterValue(Kirby\Cms\App $kirby): FilterType
    {

        $queryFilter = $kirby->request()->query()->get('filter');

        if( ! $queryFilter ) return FilterType::all;

        return FilterType::tryFrom($queryFilter) ?? FilterType::all;
    }
}
