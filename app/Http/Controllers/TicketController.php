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
        
    public function index(Request $request): AnonymousResourceCollection
    {
        $tickets = Ticket::with(['sector', 'priority'])
            ->when($request->sector_id, fn($q) => $q->where('sector_id', $request->sector_id))
            ->when($request->priority_id, fn($q) => $q->where('priority_id', $request->priority_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->min_hours, fn($q) => $q
                ->whereNotNull('started_at')
                ->whereRaw(
                    "EXTRACT(EPOCH FROM (COALESCE(finished_at, NOW()) - started_at)) / 3600 >= ?",
                    [$request->min_hours]
                )
            )
            ->when($request->max_hours, fn($q) => $q
                ->whereNotNull('started_at')
                ->whereRaw(
                    "EXTRACT(EPOCH FROM (COALESCE(finished_at, NOW()) - started_at)) / 3600 <= ?",
                    [$request->max_hours]
                )
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return TicketResource::collection($tickets);
    }
  
}