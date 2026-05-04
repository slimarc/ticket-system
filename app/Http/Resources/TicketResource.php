<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        $elapsedHours = null;

        if ($this->started_at) {
            $end          = $this->finished_at ?? now();
            $elapsedHours = round($this->started_at->diffInMinutes($end) / 60, 2);
        }

        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'requester'       => $this->requester,
            'status'          => $this->status->value,
            'sector_id'       => $this->sector_id,
            'sector'          => $this->sector->name,
            'priority_id'     => $this->priority_id,
            'priority'        => $this->priority->name,
            'priority_color'  => $this->priority->color,
            'estimated_hours' => $this->priority->estimated_hours,
            'elapsed_hours'   => $elapsedHours,
            'overdue'         => $elapsedHours !== null && $elapsedHours > $this->priority->estimated_hours,
            'solution'        => $this->solution,
            'started_at'      => $this->started_at,
            'finished_at'     => $this->finished_at,
            'created_at'      => $this->created_at,
        ];
    }
}