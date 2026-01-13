<?php

declare(strict_types=1);

namespace App\Actions\Clubs;

use App\DTOs\CreateClubDTO;
use App\Enums\ClubRole;
use App\Enums\ClubUserStatus;
use App\Models\Club;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateClubAction
{
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
                $filename = time().'_'.Str::random(6).'.'.$data->logo_path->getClientOriginalExtension();
                $path = $data->logo_path->storeAs('clubs', $filename, 'public');
                $club->logo_path = $path;
            }

            if ($data->cover_path instanceof \Illuminate\Http\UploadedFile) {
                $filename = time().'_'.Str::random(6).'.'.${$data}->cover_path->getClientOriginalExtension();
                $path = $data->cover_path->storeAs('clubs', $filename, 'public');
                $club->cover_path = $path;
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
