<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active');

            $table->text('description')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();

            $table->string('phone')->nullable();
            $table->json('address')->nullable();
            $table->json('working_hours')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();

            $table->unique(['owner_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
