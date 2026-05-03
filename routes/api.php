<?php

use App\Http\Controllers\PriorityController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::apiResource('sectors', SectorController::class)->only(['index', 'store', 'destroy']);
Route::apiResource('priorities', PriorityController::class)->only(['index', 'store', 'destroy']);
Route::apiResource('tickets', TicketController::class)->only(['index', 'store']);
Route::put('tickets/{ticket}/checkin', [TicketController::class, 'checkin']);
Route::put('tickets/{ticket}/checkout', [TicketController::class, 'checkout']);