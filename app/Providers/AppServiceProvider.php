<?php

namespace App\Providers;

use App\Comments\Formatter;
use App\Models\BuildMetric;
use App\Models\Character;
use App\Models\Comment;
use App\Models\DatabaseNotification;
use App\Models\Equipment;
use App\Models\EquipmentMod;
use App\Models\Gun;
use App\Models\Loadout;
use App\Models\Mod;
use App\Models\Overclock;
use App\Models\Patch;
use App\Models\Post;
use App\Models\Report;
use App\Models\Throwable;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('viewPulse', fn (User $user): bool => $user->can(['view-admin', 'manage-stats']));

        Relation::morphMap([
            'App\BuildMetric' => BuildMetric::class,
            'App\Character' => Character::class,
            'App\Comment' => Comment::class,
            'App\Comments\Report' => Report::class,
            'App\Comments\Vote' => Vote::class,
            'App\Equipment' => Equipment::class,
            'App\EquipmentMod' => EquipmentMod::class,
            'App\Gun' => Gun::class,
            'App\Loadout' => Loadout::class,
            'App\Mod' => Mod::class,
            'App\Notifications\DatabaseNotification' => DatabaseNotification::class,
            'App\Overclock' => Overclock::class,
            'App\Patch' => Patch::class,
            'App\Post' => Post::class,
            'App\Throwable' => Throwable::class,
            'App\User' => User::class,
        ]);

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
