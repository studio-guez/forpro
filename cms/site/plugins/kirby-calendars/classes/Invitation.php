<?php

namespace Eclypsys\KirbyCalendars;

use Kirby\Data\Data;

class Invitation extends BaseClass
{
    const FILENAME = 'invitations.json';

    public static function create(array $input): string
    {
        $id = uuid();

        $invitation = [
            'email' => $input['email'] ?? '',
            'event_id' => $input['event_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $invitations = static::list();
        $invitations[$id] = $invitation;

        Data::write(static::file(), $invitations);

        return $id;
    }

    /**
     * Finds invitations by event ID.
     *
     * @param int|string $id The event ID to filter invitations by.
     *
     * @return array Returns an array of invitations that match the given event ID.
     */
    public static function findByEventId(int|string $id): array
    {
        $invitations = static::list();
        return array_filter($invitations, function ($invitation) use ($id) {
            return $invitation['event_id'] === $id;
        });
    }
}
