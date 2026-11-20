<?php

namespace App\Providers;

use App\Comment;
use App\Comments\Formatter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Formatter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Vite's default public/build directory would shadow the /build route.
        Vite::useBuildDirectory('dist');

        RateLimiter::for('comments', function (Request $request): Limit {
            if ($request->user()?->can('moderate', Comment::class)) {
                return Limit::none();
            }

            return Limit::perMinute(config('comments.posts_per_minute'))
                ->by(($request->user()?->getKey() ?? 'guest').'|'.$request->ip())
                ->response(fn (Request $request, array $headers) => response()->json([
                    'content' => trans('comments-messages.throttle', ['seconds' => $headers['Retry-After']]),
                ], 429, $headers));
        });
    }
}
