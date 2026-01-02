<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class SubscriptionLimit extends Model
{
    protected function casts(): array
    {
        return [
            'analytics_access' => 'bool',
            'inventory_access' => 'bool',
        ];
    }
}
