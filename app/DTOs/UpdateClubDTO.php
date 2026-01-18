<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\V1\Club\UpdateClubRequest;

final class UpdateClubDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $cover_path,
        public ?string $phone,
        public ?array $address,
        public ?array $working_hours,
        public ?array $social_links
    ) {}

    public static function fromRequest(UpdateClubRequest $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            cover_path: $data['cover_path'] ?? null,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            working_hours: $data['working_hours'] ?? null,
            social_links: $data['social_links'] ?? null,
        );
    }
}
