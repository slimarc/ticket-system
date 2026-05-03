<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Open       = 'Aberto';
    case InProgress = 'Em Progresso';
    case Closed     = 'Fechado';
    case Cancelled  = 'Cancelado';
}