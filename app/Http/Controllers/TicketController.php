<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::create($request->validated());
        return response()->json(
            new TicketResource($ticket->load(['sector', 'priority'])),
            201
        );
    }

    public function checkin(Ticket $ticket): JsonResponse
    {
        if ($ticket->status === TicketStatus::Closed || $ticket->status === TicketStatus::Cancelled) {
            return response()->json(
                ['error' => "Cannot start a ticket with status '{$ticket->status->value}'."],
                422
            );
        }

        if ($ticket->status === TicketStatus::InProgress) {
            return response()->json(['error' => 'Ticket already started.'], 422);
        }

        $ticket->update([
            'status'     => TicketStatus::InProgress,
            'started_at' => now(),
        ]);

        return response()->json(new TicketResource($ticket->load(['sector', 'priority'])));
    }

    public function checkout(CheckoutRequest $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->status !== TicketStatus::InProgress) {
            return response()->json(
                ['error' => "Only in-progress tickets can be closed. Current status: '{$ticket->status->value}'."],
                422
            );
        }

        $ticket->update([
            'status'      => TicketStatus::Closed,
            'finished_at' => now(),
            'solution'    => $request->validated()['solution'],
        ]);

        return response()->json(new TicketResource($ticket->load(['sector', 'priority'])));
    }
}