<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'sector_id',
        'priority_id',
        'status',
        'requester',
        'started_at',
        'finished_at',
        'solution',
    ];

    protected $casts = [
        'status'      => TicketStatus::class,
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }
}