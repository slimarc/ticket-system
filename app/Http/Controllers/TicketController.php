<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    private TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }
    
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::create($request->validated());

        return response()->json(
            $this->transform($ticket),
            201
        );
    }

    private function transform(Ticket $ticket): TicketResource
    {
        return new TicketResource(
            $ticket->load(['sector', 'priority'])
        );
    }

    public function checkin(Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->checkin($ticket);

            return response()->json(
                $this->transform($ticket)
            );

        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function checkout(CheckoutRequest $request, Ticket $ticket): JsonResponse
    {
        try {
            $ticket = $this->ticketService->checkout(
                $ticket,
                $request->validated()['solution']
            );

            return response()->json(
                $this->transform($ticket)
            );

        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
        
    public function index(Request $request): AnonymousResourceCollection
    {
        $tickets = Ticket::with(['sector', 'priority'])
            ->when($request->filled('sector_id'), fn($q) => 
                $q->where('sector_id', $request->input('sector_id'))
            )
            ->when($request->filled('priority_id'), fn($q) => 
                $q->where('priority_id', $request->input('priority_id'))
            )
            ->when($request->filled('status'), fn($q) => 
                $q->where('status', $request->input('status'))
            )
            ->when($request->filled('min_hours'), function ($q) use ($request) {
                $q->whereNotNull('started_at')
                ->whereRaw(
                    "EXTRACT(EPOCH FROM (COALESCE(finished_at, NOW()) - started_at)) / 3600 >= ?",
                    [$request->input('min_hours')]
                );
            })
            ->when($request->filled('max_hours'), function ($q) use ($request) {
                $q->whereNotNull('started_at')
                ->whereRaw(
                    "EXTRACT(EPOCH FROM (COALESCE(finished_at, NOW()) - started_at)) / 3600 <= ?",
                    [$request->input('max_hours')]
                );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return TicketResource::collection($tickets);
    }
}