<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSectorRequest;
use App\Models\Sector;
use Illuminate\Http\JsonResponse;


class SectorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Sector::orderBy('name')->get());
    }

    public function store(StoreSectorRequest $request): JsonResponse
    {
        $sector = Sector::create($request->validated());
        return response()->json($sector, 201);
    }

    public function destroy(Sector $sector): JsonResponse
    {
        if ($sector->tickets()->exists()) {
            return response()->json(['error' => 'Sector has associated tickets.'], 422);
        }

        $sector->delete();
        return response()->json(['message' => 'Sector deleted.']);
    }
}