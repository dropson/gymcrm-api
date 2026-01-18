<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Club;

use App\Actions\Clubs\CreateClubAction;
use App\Actions\Clubs\UpdateClubACtion;
use App\DTOs\CreateClubDTO;
use App\DTOs\UpdateClubDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Club\StoreClubRequest;
use App\Http\Requests\V1\Club\UpdateClubRequest;
use App\Http\Resources\V1\ClubSummaryResource;
use App\Models\Club;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClubController extends Controller
{
    public function index(Request $request)
    {
        $clubs = $request->user()->clubs;

        return ClubSummaryResource::collection($clubs);
    }

    public function store(StoreClubRequest $request, CreateClubAction $action): JsonResponse
    {
        $this->authorize('create', Club::class);
        $club = $action->handle(CreateClubDTO::fromRequest($request));

        return $this->success('Club was created', $club);
    }

    public function show(Club $club): Club
    {
        $this->authorize('view', $club);

        return $club;
    }

    public function update(UpdateClubRequest $request, Club $club, UpdateClubACtion $action): JsonResponse
    {

        $this->authorize('update', $club);
        $action->handle($club, UpdateClubDTO::fromRequest($request));

        return $this->ok('Club updated successfully');
    }
}
