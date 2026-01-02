<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_limits', function (Blueprint $table): void {
            $table->id();
            $table->string('plan')->unique();
            $table->integer('max_clubs')->nullable();
            $table->integer('max_managers_per_club')->nullable();
            $table->integer('max_trainers_per_club')->nullable();
            $table->boolean('analytics_access')->default(false);
            $table->boolean('inventory_access')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_limits');
    }
};
