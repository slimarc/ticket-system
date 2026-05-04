<?php

namespace App\Services;

use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Models\TicketTracking;
use DomainException;


class TicketService
{
    public function checkin(Ticket $ticket): Ticket
    {
        if ($ticket->status === TicketStatus::Closed || $ticket->status === TicketStatus::Cancelled) {
            throw new \DomainException("Cannot start a ticket with status '{$ticket->status->value}'.");
        }

        if ($ticket->status === TicketStatus::InProgress) {
            throw new \DomainException('Ticket already started.');
        }

        $ticket->update([
            'status'     => TicketStatus::InProgress,
            'started_at' => now(),
        ]);

        TicketTracking::create([
            'ticket_id' => $ticket->id,
            'action_type' => 'STARTED',
        ]);

        return $ticket;
    }

    public function checkout(Ticket $ticket, string $solution): Ticket
    {
        if ($ticket->status !== TicketStatus::InProgress) {
            throw new \DomainException(
                "Only in-progress tickets can be closed. Current status: '{$ticket->status->value}'."
            );
        }

        $ticket->update([
            'status'      => TicketStatus::Closed,
            'finished_at' => now(),
            'solution'    => $solution,
        ]);

        TicketTracking::create([
            'ticket_id' => $ticket->id,
            'action_type' => 'FINISHED',
            'notes' => $solution,
        ]);

        return $ticket;
    }
}