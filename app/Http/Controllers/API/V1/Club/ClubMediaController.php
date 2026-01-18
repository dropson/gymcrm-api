<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Club;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ClubMediaController extends Controller
{
    public function __construct(private readonly MediaService $mediaService) {}

    public function updateLogo(Request $request, Club $club): JsonResponse
    {
        $this->authorize('update', $club);
        $request->validate([
            'logo' => ['required', 'image', 'max:2048'],
        ]);
        $path = $this->mediaService->upload($request->file('logo'), 'clubs/logos', $club->logo_path);
        $club->update(['logo_path' => $path]);

        return $this->ok('Logo updated');
    }

    public function deleteLogo(Club $club): JsonResponse
    {

        $this->authorize('update', $club);

        $this->mediaService->delete($club->logo_path);
        $club->update(['logo_path' => null]);

        return $this->ok('Logo removed');
    }
}
