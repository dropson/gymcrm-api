<?php

declare(strict_types=1);

namespace App\Actions\Clubs;

use App\DTOs\UpdateClubDTO;
use App\Models\Club;
use Illuminate\Support\Facades\DB;

final class UpdateClubACtion
{
    public function handle(Club $club, UpdateClubDTO $data): void
    {
        DB::transaction(function () use ($club, $data): void {
            $club->update([
                'name' => $data->name,
                'description' => $data->description,
                'phone' => $data->phone,
                'address' => $data->address,
                'working_hours' => $data->working_hours,
                'social_links' => $data->social_links,
            ]);
        });
    }
}
