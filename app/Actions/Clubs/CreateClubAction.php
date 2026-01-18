<?php

declare(strict_types=1);

namespace App\Actions\Clubs;

use App\DTOs\CreateClubDTO;
use App\Enums\ClubRole;
use App\Enums\ClubUserStatus;
use App\Models\Club;
use App\Services\MediaService;
use Illuminate\Support\Facades\DB;

final readonly class CreateClubAction
{
    public function __construct(
        private MediaService $mediaService
    ) {}

    public function handle(CreateClubDTO $data)
    {
        return DB::transaction(function () use ($data): Club {
            $club = new Club([
                'owner_id' => $data->owner_id,
                'name' => $data->name,
                'status' => 'active',
                'description' => $data->description,
                'address' => $data->address,
                'phone' => $data->phone,
                'working_hours' => $data->working_hours,
                'social_links' => $data->social_links,
            ]);

            if ($data->logo_path instanceof \Illuminate\Http\UploadedFile) {
                $club->logo_path = $this->mediaService->upload(
                    $data->logo_path,
                    'clubs/logos'
                );
            }

            if ($data->cover_path instanceof \Illuminate\Http\UploadedFile) {
                $club->cover_path = $this->mediaService->upload(
                    $data->cover_path,
                    'clubs/covers'
                );
            }

            $club->save();

            $club->users()->attach($data->owner_id, [
                'role' => ClubRole::Owner->value,
                'permissions' => null,
                'status' => ClubUserStatus::Active->value,
            ]);

            return $club;
        });
    }
}
