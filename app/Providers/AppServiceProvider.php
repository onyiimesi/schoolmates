<?php

namespace App\Providers;

use App\Models\Result;
use App\Models\StudentScore;
use App\Observers\ResultObserver;
use App\Observers\StudentScoreObserver;
use Illuminate\Http\Request;
use App\Services\SlowQueryMonitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        RateLimiter::for('apis', function (Request $request) {
            return $request->user() ?
                Limit::perMinute(60)->by($request->ip())
                : Limit::perMinute(10)->by($request->ip());
        });

        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();

        app(SlowQueryMonitor::class)->register();

        Result::observe(ResultObserver::class);
        StudentScore::observe(StudentScoreObserver::class);
    }

    /**
     * Configure the application's command.
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction(),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        //Model::shouldBeStrict();
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();
    }

    /**
     * Configure the application's URL.
     */
    private function configureUrl(): void
    {
        URL::formatScheme(true);
    }
}
