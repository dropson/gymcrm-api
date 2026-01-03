<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckSubscriptionFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        $limits = app(SubscriptionService::class)->limits($user);
        if (! data_get($limits, $feature.'_access')) {
            abort(403, 'Upgrade your subscription');
        }

        return $next($request);
    }
}
