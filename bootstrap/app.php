<?php

declare(strict_types=1);

use App\Exceptions\SubscriptionLimitException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('api')->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => App\Http\Middleware\EnsureEmailIsVerified::class,
            'subscription.feature' => App\Http\Middleware\CheckSubscriptionFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(fn (SubscriptionLimitException $e, $request) => response()->json([
            'message' => $e->getMessage(),
            'code' => 'SUBSCRIPTION_LIMIT',
            'upgrade_required' => true,
            'resource' => $e->resource,
            'limit' => $e->limit,
            'plan' => $e->plan,
        ], Response::HTTP_FORBIDDEN));
    })->create();
