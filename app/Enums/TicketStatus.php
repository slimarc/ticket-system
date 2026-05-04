<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open       = 'OPEN';
    case InProgress = 'IN_PROGRESS';
    case Closed     = 'CLOSED';
    case Cancelled  = 'CANCELLED';
}