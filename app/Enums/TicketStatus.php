<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open       = 'Open';
    case InProgress = 'In Progress';
    case Closed     = 'Closed';
    case Cancelled  = 'Cancelled';
}