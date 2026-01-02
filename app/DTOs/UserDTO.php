<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\Auth\RegisterUserRequest;

final class UserDTO
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
    ) {}

    public static function fromRequest(RegisterUserRequest $request): self
    {

        $data = $request->validated();

        return new self(
            email: $data['email'],
            name: $data['name'],
            password: $data['password']
        );
    }
}
