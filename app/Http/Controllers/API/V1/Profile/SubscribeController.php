<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Profile;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class SubscribeController extends Controller
{
    public function update(Request $request, SubscriptionService $subscriptions)
    {
        $request->validate([
            'plan' => ['required', Rule::in(['free', 'premium', 'unlimited'])],
        ]);

        $subscriptions->upgrade($request->user(), $request->string('plan')->toString());

        return response()->json([
            'message' => 'Subscription updated',
        ]);
    }
}
