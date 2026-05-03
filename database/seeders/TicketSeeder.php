<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Models\Priority;
use App\Models\Sector;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $sectorIds = Sector::pluck('id', 'name');
        $priorityIds = Priority::pluck('id', 'name');

        $tickets = [
            [
                'title' => 'Workstation cannot connect to VPN',
                'description' => 'User is unable to connect to the company VPN from the finance laptop.',
                'sector_id' => $sectorIds['IT'],
                'priority_id' => $priorityIds['High'],
                'status' => TicketStatus::Open,
                'requester' => 'Ana Souza',
            ],
            [
                'title' => 'Payroll report access request',
                'description' => 'Needs access to the monthly payroll report before closing.',
                'sector_id' => $sectorIds['HR'],
                'priority_id' => $priorityIds['Medium'],
                'status' => TicketStatus::InProgress,
                'requester' => 'Bruno Lima',
                'started_at' => now()->subHours(2),
            ],
            [
                'title' => 'Invoice export failing',
                'description' => 'The invoice export returns an error when filtering the current month.',
                'sector_id' => $sectorIds['Finance'],
                'priority_id' => $priorityIds['Critical'],
                'status' => TicketStatus::Closed,
                'requester' => 'Carla Mendes',
                'started_at' => now()->subHours(3),
                'finished_at' => now()->subHour(),
                'solution' => 'Fixed the export query filter and regenerated the invoice file.',
            ],
            [
                'title' => 'Warehouse printer replacement',
                'description' => 'Label printer in operations is printing blank labels.',
                'sector_id' => $sectorIds['Operations'],
                'priority_id' => $priorityIds['Low'],
                'status' => TicketStatus::Cancelled,
                'requester' => 'Diego Santos',
                'solution' => 'Request cancelled after printer resumed normal operation.',
            ],
            [
                'title' => 'New employee onboarding setup',
                'description' => 'Create accounts and prepare equipment for a new hire.',
                'sector_id' => $sectorIds['IT'],
                'priority_id' => $priorityIds['Medium'],
                'status' => TicketStatus::Open,
                'requester' => 'Elisa Rocha',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::updateOrCreate(
                ['title' => $ticket['title']],
                $ticket
            );
        }
    }
}
