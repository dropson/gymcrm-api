<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

final class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'status',
        'description',
        'logo_path',
        'cover_path',
        'phone',
        'address',
        'working_hours',
        'social_links',
    ];

    protected $casts = [
        'status' => 'bool',
        'working_hours' => 'json',
        'address' => 'json',
        'social_links' => 'json',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'permissions'])
            ->withTimestamps();
    }

    protected static function booted(): void
    {
        self::creating(function (Club $club): void {
            if (! $club->slug) {
                $club->slug = self::generateUniqueSlug($club);
            }
        });
    }

    protected static function generateUniqueSlug(self $club): string
    {
        $parts = [$club->name];

        if ($city = data_get($club->address, 'city')) {
            $parts[] = $city;
        }

        if ($street = data_get($club->address, 'street')) {
            $parts[] = $street;
        }

        $base = Str::slug(implode(' ', $parts));
        $slug = $base;
        $i = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = sprintf('%s-%d', $base, $i);
            $i++;
        }

        return $slug;
    }
}
