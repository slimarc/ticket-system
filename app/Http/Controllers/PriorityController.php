<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriorityRequest;
use App\Models\Priority;
use Illuminate\Http\JsonResponse;

class PriorityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Priority::orderBy('estimated_hours')->get());
    }

    public function store(StorePriorityRequest $request): JsonResponse
    {
        $priority = Priority::create($request->validated());
        return response()->json($priority, 201);
    }

    public function destroy(Priority $priority): JsonResponse
    {
        if ($priority->tickets()->exists()) {
            return response()->json(['error' => 'Priority has associated tickets.'], 422);
        }

        $priority->delete();
        return response()->json(['message' => 'Priority deleted.']);
    }
}