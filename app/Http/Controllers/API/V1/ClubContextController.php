<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\ClubContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClubContextController extends Controller
{
    public function __invoke(Request $request, Club $club, ClubContextService $services): JsonResponse
    {
        return response()->json($services->build($request->user(), $club));
    }
}
