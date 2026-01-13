<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\V1\Club\StoreClubRequest;
use Illuminate\Http\UploadedFile;

final class CreateClubDTO
{
    public function __construct(
        public int $owner_id,
        public string $name,
        public ?string $description,
        public ?UploadedFile $logo_path,
        public ?string $cover_path,
        public string $phone,
        public array $address,
        public ?array $working_hours,
        public ?array $social_links
    ) {}

    public static function fromRequest(StoreClubRequest $request): self
    {
        $data = $request->validated();

        return new self(
            owner_id: $request->user()->id,
            name: $data['name'],
            description: $data['description'] ?? null,
            logo_path: $data['logo_path'] ?? null,
            cover_path: $data['cover_path'] ?? null,
            phone: $data['phone'],
            address: $data['address'],
            working_hours: $data['working_hours'] ?? null,
            social_links: $data['social_links'] ?? null,
        );
    }
}
